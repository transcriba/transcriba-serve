<?php

function getBaseUrl($context) {
  return "http://".$context['serverHost'];
}

function getManuscriptUrl($context) {
  return getBaseUrl($context) . "/index.php?id={id}";
}

function getImageUrl($context) {
  return getBaseUrl($context) . "/index.php?id={id}&download=true";
}

function getManuscriptDirectory() {
  return "manuscripts/";
}

function getBrowseUrl($context) {
  return getBaseUrl($context) . "/index.php?search=true&page={page}";
}
