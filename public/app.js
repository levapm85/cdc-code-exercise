"use strict";
let xhr = new XMLHttpRequest();
function get_message(msgId) {
    let params = JSON.stringify({ message_id: msgId });
    xhr.open('GET', '/message.json');
    xhr.setRequestHeader("Content-type", "application/json; charset=utf-8");
    xhr.send(params);
}
xhr.onload = function() {
    let message = JSON.parse(this.responseText);
    let html = '';
    if(message.id) {
        html = `<h2>${message.title}</h2>`;
        html += `<div class="author"><strong>From:</strong> ${message.from} - <strong>To:</strong> ${message.to}</div>`;
        html += `<textarea class="content" cols="20" rows="5" readonly>${message.body}</textarea>`;
        const created = new Date(message.created);
        const updated = new Date(message.updated);
        html += `<ul class="meta">`;
        html += `<li class="created"><strong>Created:</strong> ${created.toLocaleDateString()}</li>`;
        html += `<li class="updated"><strong>Updated:</strong> ${updated.toLocaleDateString()}</li>`;
        html += `</ul>`;
    }
    document.querySelector('.message-request').innerHTML = html;

}
xhr.onerror = function() {
    alert("Request failed");
};
