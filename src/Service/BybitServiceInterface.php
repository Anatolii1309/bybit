<?php

namespace Drupal\bybit\Service;

/**
 * Implement BybitService Interface.
 */
interface BybitServiceInterface {

  /**
   * The url for testnet.
   */
  const URL_TESTNET = 'https://api-testnet.bybit.com';

  /**
   * The url for testnet.
   */
  const URL_PROD = 'https://api.bybit.com';

  /**
   * Get the url for the request.
   *
   * @param string $uri
   *   Uri resource.
   *   The url string must start with a slash and end without a query string.
   *   For example '/v2/private/wallet/balance'.
   * @param array $params
   *   Params for require.
   *   An array consisting of a key and a value.
   *   For example ["symbol" =>"BTCUSD"].
   *
   * @return string
   *   Return full URL.
   */
  public function getSignedParams(string $uri, array $params = []): string;

  /**
   * Get wallet balance info.
   *
   * @return array
   *   Return array data.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getWalletBalance(): array;

  /**
   * Get wallet fund records.
   *
   * This endpoint also shows exchanges from the Asset Exchange, where the types
   * for the exchange are ExchangeOrderWithdraw and ExchangeOrderDeposit.
   *
   * @return array
   *   Return array data.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function walletFundRecords(): array;

}
