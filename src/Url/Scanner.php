<?php
namespace Chrisjsimpson\ModernPHP\Url;

class Scanner
{
  /**
   * @var array An array of Urls
   */
  protected $urls;

  /**
   * @var \GuzzleHttp\Client
   */
  protected $httpClient;

  /**
   * Constructor
   * @param array $urls An array of URLs to scan
   */
  public function __construct(array $urls)
  {
    $this->urls = $urls;
    $this->httpClient = new \GuzzleHttp\Client();
  }

  /** 
   * Get invalud URLs
   * @return array
   */
  public function getInvalidUrls()
  {
    $invalidUrls = [];
    foreach ($this->urls as $url) {
      try {
        $statusCode = $this->getStatusCodeForUrl($url);
      } catch (\Exception $e) {
        $statusCode = 500;
      }

      if ($statusCode >= 400) {
        array_push($invalidUrls, [
          'url' => $url,
          'status' => $statusCode
        ]);
      }
    }
  }

  /**
   * Get HTTP status code for URL
   * @param string $url The remote URL
   * @return int the HTTP status code
   */
  protected function getStatusCodeForUrl($url)
  {
    $httpResponse = $this->httpClient->get($url);

    return $httpResponse->getStatusCode();
  }
}
