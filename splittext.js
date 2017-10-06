/**
 * Created by Eric on 10/6/2017.
 */

function animateChars(arr) {
  for(var i = 0; i < arr.length; i++){
    TweenLite.from(arr[i], 0.2, {y:4,alpha:0});
  }
}

function splitText(message) {
  return appendTags(splitChars(message));
}

function appendTags(str) {
  var arr = [];
  for(var i = 0; i < str.length; i++){
    var c = str[i];
    arr.push('<span class="char">'+c+'</span>');
  }
  return '<div>'+arr+'</div>';
}

function splitChars(str){
  var arr = str.split('');
  return arr;
}

function keepUsername(un) {
  var matches = /(\w+:).+/g.exec(un);
  return matches[1];
}

function escapeUsername(un) {
  var matches = /\w+:(.+)/g.exec(un);
  return matches[1];
}
