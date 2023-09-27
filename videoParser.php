<?
abstract class VideoHostingParser
{
    protected $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    abstract public function getHostingName(): string;

    abstract public function getVideoId(): string;

    abstract public function getEmbedHtml(): string;
}

class YoutubeParser extends VideoHostingParser
{
    public function getHostingName(): string
    {
        return 'Youtube';
    }

    public function getVideoId(): string
    {
      $parsedUrl = parse_url($this->url);

      if ($parsedUrl['host'] == 'youtu.be') {
          return ltrim($parsedUrl['path'],'/');
      }
  
      if ($parsedUrl['host'] == 'www.youtube.com') {
          parse_str($parsedUrl['query'], $queryString);
          return $queryString['v'];
      }
    }

    public function getEmbedHtml(): string
    {
        return '<iframe src="https://www.youtube.com/embed/' . $this->getVideoId() . '"></iframe>';
    }
}

class VimeoParser extends VideoHostingParser
{
    public function getHostingName(): string
    {
        return 'Vimeo';
    }

    public function getVideoId(): string
    {
        return substr(parse_url($this->url, PHP_URL_PATH), 1);
    }

    public function getEmbedHtml(): string
    {
        return '<iframe src="https://player.vimeo.com/video/' . $this->getVideoId() . '"></iframe>';
    }
}

class VideoHostingParserFactory
{
    public static function create(string $url): VideoHostingParser
    {
      try {
        if (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false) {
            return new YoutubeParser($url);
        }

        if (strpos($url, 'vimeo.com') !== false) {
            return new VimeoParser($url);
        }
        
        throw new InvalidArgumentException('Unsupported video hosting');

       } catch (InvalidArgumentException $e) {
           echo $e->getMessage();
           exit();
       }
       
    }
}

$parserExempleYoutu_be = VideoHostingParserFactory::create('https://youtu.be/homqyBxHwis');
$parserExempleYoutube = VideoHostingParserFactory::create('https://www.youtube.com/watch?v=G1IbRujko-A');
$parserExempleVimeo = VideoHostingParserFactory::create('https://vimeo.com/225408543');

echo $parserExempleYoutu_be->getHostingName();
?><br><?
echo $parserExempleYoutu_be->getVideoId(); 
?><br><?
echo $parserExempleYoutu_be->getEmbedHtml();
?><br><br><br><?

echo $parserExempleYoutube->getHostingName();
?><br><?
echo $parserExempleYoutube->getVideoId(); 
?><br><?
echo $parserExempleYoutube->getEmbedHtml();
?><br><br><br><?

echo $parserExempleVimeo->getHostingName();
?><br><?
echo $parserExempleVimeo->getVideoId(); 
?><br><?
echo $parserExempleVimeo->getEmbedHtml();
?><br><?