<?php

namespace Drupal\bybit\Service;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Config\ConfigFactoryInterface;
use GuzzleHttp\ClientInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The Bybit service.
 */
class BybitService implements BybitServiceInterface {

  /**
   * The configuration factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The HTTP client to fetch the feed data with.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * Construct the Bybit service.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory.
   * @param \GuzzleHttp\ClientInterface $http_client
   *   The Guzzle HTTP client.
   */
  public function __construct(ConfigFactoryInterface $config_factory, ClientInterface $http_client) {
    $this->configFactory = $config_factory;
    $this->httpClient = $http_client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('http_client')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getSignedParams(string $uri, array $params = []): string {
    $url = $this->configFactory->get('bybit.settings')->get('testnet') ? self::URL_TESTNET : self::URL_PROD;
    $params = array_merge(['api_key' => $this->configFactory->get('bybit.settings')->get('api_key')], $params);
    $params = array_merge(['timestamp' => time() * 1000], $params);
    ksort($params);
    $signature = hash_hmac(
      'sha256',
      urldecode(http_build_query($params)),
      $this->configFactory->get('bybit.settings')->get('api_secret')
    );
    $param = http_build_query($params) . "&sign=$signature";

    return $url . $uri . "?" . $param;
  }

  /**
   * {@inheritdoc}
   */
  public function getWalletBalance(): array {
    $url = $this->getSignedParams('/v2/private/wallet/balance', []);
    $response = $this->httpClient->request('GET', ($url));

    return Json::decode((string) $response->getBody());
  }

  /**
   * {@inheritdoc}
   */
  public function walletFundRecords(): array {
    $url = $this->getSignedParams('/v2/private/wallet/fund/records', []);
    $response = $this->httpClient->request('GET', ($url));

    return Json::decode((string) $response->getBody());
  }

}
