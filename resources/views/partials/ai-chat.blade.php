{{-- Khung chat tư vấn AI — gọi sang app marketing (agent.tranhdali.vn/api/web-chat) --}}
<div id="daliAiLauncher" class="dali-ai-launcher" title="Tư vấn cùng DALI" aria-label="Mở chat tư vấn">
  <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="#fff" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.4 8.4 0 0 1-9 8.4 8.7 8.7 0 0 1-3.8-.9L3 20l1-5.2a8.4 8.4 0 1 1 17-3.3Z"/></svg>
  <span class="dali-ai-pulse"></span>
</div>

<div id="daliAiPanel" class="dali-ai-panel" hidden>
  <div class="dali-ai-head">
    <div>
      <div class="dali-ai-title">Tư vấn cùng DALI ✨</div>
      <div class="dali-ai-sub">Thường trả lời ngay</div>
    </div>
    <button id="daliAiClose" class="dali-ai-close" aria-label="Đóng">&times;</button>
  </div>
  <div id="daliAiBody" class="dali-ai-body"></div>
  <form id="daliAiForm" class="dali-ai-form">
    <input id="daliAiInput" type="text" placeholder="Nhập câu hỏi của bạn..." autocomplete="off" maxlength="1000" />
    <button type="submit" aria-label="Gửi">➤</button>
  </form>
</div>

<style>
.dali-ai-launcher{position:fixed;left:16px;bottom:18px;z-index:901;width:54px;height:54px;border-radius:50%;background:linear-gradient(135deg,#b5651d,#8a3f12);display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 6px 18px rgba(138,63,18,.4);animation:daliAiIn .4s ease-out}
.dali-ai-launcher:hover{transform:scale(1.08)}
.dali-ai-pulse{position:absolute;inset:0;border-radius:50%;box-shadow:0 0 0 0 rgba(181,101,29,.55);animation:daliAiRing 1.8s infinite}
.dali-ai-panel{position:fixed;left:16px;bottom:18px;z-index:902;width:340px;max-width:calc(100vw - 32px);height:480px;max-height:calc(100vh - 36px);background:#fff;border-radius:16px;box-shadow:0 14px 44px rgba(0,0,0,.26);display:flex;flex-direction:column;overflow:hidden;font-family:inherit;animation:daliAiIn .25s ease-out}
.dali-ai-head{background:linear-gradient(135deg,#b5651d,#8a3f12);color:#fff;padding:13px 15px;display:flex;align-items:center;justify-content:space-between}
.dali-ai-title{font-weight:700;font-size:15px}
.dali-ai-sub{font-size:11.5px;opacity:.85;margin-top:2px}
.dali-ai-close{background:none;border:none;color:#fff;font-size:26px;line-height:1;cursor:pointer;opacity:.9}
.dali-ai-body{flex:1;overflow-y:auto;padding:14px;background:#faf6f0;display:flex;flex-direction:column;gap:9px}
.dali-ai-msg{max-width:84%;padding:9px 12px;border-radius:13px;font-size:13.5px;line-height:1.5;white-space:normal;word-wrap:break-word}
.dali-ai-bot{background:#fff;border:1px solid #efe3d5;align-self:flex-start;border-bottom-left-radius:4px;color:#3d2b1f}
.dali-ai-user{background:#b5651d;color:#fff;align-self:flex-end;border-bottom-right-radius:4px}
.dali-ai-msg a{color:#8a3f12;font-weight:600}
.dali-ai-user a{color:#fff;text-decoration:underline}
.dali-ai-typing{align-self:flex-start;color:#97826f;font-size:13px;font-style:italic;padding:4px 12px}
.dali-ai-form{display:flex;gap:8px;padding:10px;border-top:1px solid #eee;background:#fff}
.dali-ai-form input{flex:1;border:1px solid #e0d4c4;border-radius:22px;padding:9px 14px;font-size:13.5px;outline:none}
.dali-ai-form input:focus{border-color:#b5651d}
.dali-ai-form button{width:40px;height:40px;border-radius:50%;border:none;background:#b5651d;color:#fff;font-size:16px;cursor:pointer;flex-shrink:0}
.dali-ai-form button:disabled{opacity:.5;cursor:default}
@keyframes daliAiIn{from{opacity:0;transform:translateY(14px) scale(.96)}to{opacity:1;transform:none}}
@keyframes daliAiRing{0%{box-shadow:0 0 0 0 rgba(181,101,29,.5)}70%{box-shadow:0 0 0 14px rgba(181,101,29,0)}100%{box-shadow:0 0 0 0 rgba(181,101,29,0)}}
@media(max-width:600px){.dali-ai-launcher{left:12px;bottom:14px;width:50px;height:50px}.dali-ai-panel{left:12px;bottom:14px}}
</style>

<script>
(function(){
  var AGENT = 'https://agent.tranhdali.vn';
  var launcher = document.getElementById('daliAiLauncher');
  var panel = document.getElementById('daliAiPanel');
  var body = document.getElementById('daliAiBody');
  var form = document.getElementById('daliAiForm');
  var input = document.getElementById('daliAiInput');
  var sendBtn = form.querySelector('button');
  var started = false;

  function sid(){
    try{
      var k = localStorage.getItem('dali_ai_sid');
      if(!k){ k = 'w' + Date.now().toString(36) + Math.random().toString(36).slice(2,8); localStorage.setItem('dali_ai_sid', k); }
      return k;
    }catch(e){ return 'anon'; }
  }
  function esc(s){ return String(s).replace(/[&<>"]/g, function(c){ return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]; }); }
  function render(t){
    return esc(t).replace(/(https?:\/\/[^\s]+)/g, function(u){ return '<a href="'+u+'" target="_blank" rel="noopener">'+u+'</a>'; }).replace(/\n/g,'<br/>');
  }
  function add(text, who){
    var d = document.createElement('div');
    d.className = 'dali-ai-msg dali-ai-' + who;
    d.innerHTML = render(text);
    body.appendChild(d); body.scrollTop = body.scrollHeight;
    return d;
  }
  function typing(on){
    var t = document.getElementById('daliAiTyping');
    if(on && !t){ t=document.createElement('div'); t.id='daliAiTyping'; t.className='dali-ai-typing'; t.textContent='DALI đang soạn...'; body.appendChild(t); body.scrollTop=body.scrollHeight; }
    if(!on && t) t.remove();
  }
  function open(){
    panel.hidden=false; launcher.style.display='none'; input.focus();
    if(!started){ started=true; add('Chào bạn 👋 Mình là trợ lý của DALI. Bạn đang tìm tranh để tự tô, làm quà tặng, hay muốn đặt tranh từ ảnh riêng? Mình tư vấn giúp nhé!', 'bot'); }
  }
  function close(){ panel.hidden=true; launcher.style.display='flex'; }

  launcher.addEventListener('click', open);
  document.getElementById('daliAiClose').addEventListener('click', close);

  form.addEventListener('submit', function(e){
    e.preventDefault();
    var msg = input.value.trim();
    if(!msg) return;
    add(msg, 'user'); input.value=''; sendBtn.disabled=true; typing(true);
    fetch(AGENT + '/api/web-chat', {
      method:'POST', headers:{'Content-Type':'application/json'},
      body: JSON.stringify({ session_id: sid(), message: msg })
    })
    .then(function(r){ return r.json(); })
    .then(function(d){ typing(false); add(d.reply || 'Xin lỗi, shop chưa trả lời được. Bạn để lại SĐT giúp shop nhé!', 'bot'); })
    .catch(function(){ typing(false); add('Mạng đang trục trặc 😢 Bạn thử lại hoặc nhắn Zalo/Messenger giúp shop nha.', 'bot'); })
    .finally(function(){ sendBtn.disabled=false; input.focus(); });
  });
})();
</script>
