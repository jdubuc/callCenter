$('#build').bind('click', function() {
  var inputText = $('#buildInput').val();
  var regMessage = /^#(\d):\s([^#.]*)/gm;
  var messages = inputText.split(regMessage);
  $('#phone').empty();
  for (var i=1; i<messages.length; i=i+3) {
    addMsg(messages[i], messages[i+1]);
  }
  return false;
})

$('#send').bind('submit', function() {
  var msgText = $('#msgInput').val();
  $('#msgInput').val('');
  
  if (msgText != '') addMsg(1, msgText);
  
  $('#phone').animate({ scrollTop: $('#phone').height() }, 600);
  
  return false;
})

function addMsg(people, msg) {
  
  var side = 'right';
  var $_phone = $('#phone');
  var $_lastMessage = $('#phone .message:last');
  
  if (people == 1) side = 'right';
  if (people == 2) side = 'left';
  
  if ($_lastMessage.hasClass(side)) {
    
    $_lastMessage.append(
      $('<div>').addClass('message-text').text(msg)
    )
    
  } else {
    
    $_phone.append(
      $('<div>').addClass('message '+side).append(
        $('<div>').addClass('message-text').text(msg)
      )
    )
    
  }
  
}