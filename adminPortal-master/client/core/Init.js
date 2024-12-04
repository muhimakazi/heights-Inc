const SERVER_ADDRESS = "http://localhost";
// const SERVER_ADDRESS = "https://admin.torusguru.com";
// const REMOTE_SERVER_ADDRESS = "https://admin.torusguru.com";
const REMOTE_SERVER_ADDRESS = "http://localhost";
const APP_NAME = "/thefuture/adminPortal";
// const APP_NAME = "";
const CORE_APP_NAME = "/thefuture/adminPortal";
// const CORE_APP_NAME = "";
const APP_BASE_URL = SERVER_ADDRESS + APP_NAME;
const API_V1_ENDPOINT = REMOTE_SERVER_ADDRESS + CORE_APP_NAME + "/api/v1";
const CURRENCY = "RWF";
const CURRENCY_TEXT = "Rwandan Francs";
export const SALT = "LFUCJITENMTERFU#137";

function getAppBaseURL() {
  return APP_BASE_URL;
}

function getApiV1URL() {
  return API_V1_ENDPOINT;
}

function getServerAddress() {
  return SERVER_ADDRESS;
}

function getRemoteServerAddress() {
  return REMOTE_SERVER_ADDRESS;
}

function getCurrency() {
  return CURRENCY;
}

function getCurrencyInLetter() {
  return CURRENCY_TEXT;
}

export {
  getAppBaseURL,
  getApiV1URL,
  getServerAddress,
  getRemoteServerAddress,
  getCurrency,
  getCurrencyInLetter,
};
