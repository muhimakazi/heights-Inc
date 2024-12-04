import * as Init from "../core/Init.js";
import * as HTTP from "../core/http.js";

export function ENCODE_STRING(string) {
  return btoa(request_string + Init.SALT);
}

export function ENCODE_BASE64_STRING(string) {
  return btoa(string);
}

export function DECODE_BASE64_STRING(string) {
  return atob(string);
}

export function BASIC_AUTH(username, password) {
  return "Basic " + ENCODE_BASE64_STRING(username + ":" + password);
}

export function BEARER_AUTH(token) {
  return "Bearer " + token;
}
