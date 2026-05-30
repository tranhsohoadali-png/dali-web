@if(!empty($settings['zalo_oa_id']))
<div class="zalo-chat-widget" data-oaid="{{ $settings['zalo_oa_id'] }}" data-welcome-message="Xin chào! Tôi có thể giúp gì cho bạn?" data-autopopup="0" data-width="350" data-height="420"></div>
<script src="https://sp.zalo.me/plugins/sdk.js"></script>
@endif
