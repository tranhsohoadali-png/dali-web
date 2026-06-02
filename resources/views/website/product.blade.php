<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>{{ $product->name }} | DALI</title>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.css" rel="stylesheet">
<style>[class^="ri-"],[class*=" ri-"]{vertical-align:-.125em;font-style:normal;line-height:1}</style>
<style>
:root{--g:#6BBF1F;--gb:#8ED63A;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--gn:#C6F135;--pk:#FF8FB1;--pkl:#FFF0F5;--bd:#C8E89A;--bd2:#A8D870;--bg:#F2FDE8;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8FC860;--char:#1C3A0A;--shad:rgba(58,122,10,.10)}
*{box-sizing:border-box;margin:0;padding:0}html{scroll-behavior:smooth}
body{font-family:'Be Vietnam Pro',sans-serif;background:var(--bg);color:var(--tx);line-height:1.6}
::-webkit-scrollbar{width:5px}::-webkit-scrollbar-track{background:var(--bg)}::-webkit-scrollbar-thumb{background:var(--bd2);border-radius:4px}
nav{position:sticky;top:0;z-index:100;background:linear-gradient(175deg,#1C5200,#2D7A08,#3A9A12);height:68px;padding:0 5%;display:flex;align-items:center;justify-content:space-between;transition:transform .3s}
nav.nav-hidden{transform:translateY(-100%)}
.nav-logo{height:38px;object-fit:contain;display:block;filter:brightness(0) invert(1)}
.nav-links{display:flex;gap:28px;list-style:none}
.nav-links a{text-decoration:none;color:rgba(255,255,255,.75);font-size:14px;font-weight:500;border-bottom:2px solid transparent;padding-bottom:2px;transition:all .2s}
.nav-links a:hover,.nav-links a.active{color:#fff;border-bottom-color:var(--gn)}
.nav-right{display:flex;align-items:center;gap:12px}
.nav-phone{font-size:13px;font-weight:600;color:rgba(255,255,255,.85);text-decoration:none}
.btn-order-nav{background:var(--gn);color:var(--char);border:none;border-radius:50px;padding:9px 20px;font-size:13px;font-weight:800;cursor:pointer;text-decoration:none;display:inline-block;transition:all .2s}
.btn-order-nav:hover{background:#fff;transform:translateY(-2px)}
.nav-hamburger{display:none;flex-direction:column;gap:5px;cursor:pointer;background:none;border:none}
.nav-hamburger span{display:block;width:22px;height:2px;background:#fff}
.mobile-nav{display:none;position:fixed;top:68px;left:0;right:0;bottom:0;background:linear-gradient(175deg,#1C5200,#2D7A08);z-index:99;padding:28px 5%;flex-direction:column;gap:4px}
.mobile-nav.open{display:flex}
.mobile-nav a{font-size:17px;font-weight:600;color:rgba(255,255,255,.8);text-decoration:none;padding:13px 16px;border-bottom:1px solid rgba(255,255,255,.1);border-radius:8px}
.sakura-strip{background:linear-gradient(90deg,#fff8fa,#f6ffe8,#fff,#f6ffe8,#fff8fa);border-bottom:1px solid #F0EBF8;padding:7px 5%;display:flex;align-items:center;gap:6px}
.petal{font-size:15px;animation:drift 5s ease-in-out infinite;display:inline-block}
.petal:nth-child(2){animation-delay:1s}.petal:nth-child(3){animation-delay:2s}.petal:nth-child(4){animation-delay:3s}
@keyframes drift{0%,100%{transform:translateY(0)}50%{transform:translateY(-4px)}}
.sak-text{font-size:10px;color:#B8D8A0;letter-spacing:2.5px;font-weight:700;margin-left:8px}
/* BREADCRUMB */
.breadcrumb{padding:14px 5%;display:flex;align-items:center;gap:8px;font-size:13px;color:var(--tx3)}
.breadcrumb a{color:var(--tx2);text-decoration:none;font-weight:500}
.breadcrumb a:hover{color:var(--g)}
.breadcrumb-sep{color:var(--bd2)}
/* PRODUCT DETAIL */
.product-detail{padding:32px 5% 60px;max-width:1100px;margin:0 auto}
.detail-grid{display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:start}
/* Image */
.prod-img-box{position:relative;border-radius:20px;overflow:hidden;border:1.5px solid var(--bd);box-shadow:0 8px 32px var(--shad)}
.prod-img-box img{width:100%;height:480px;object-fit:cover;display:block}
.prod-badge{position:absolute;top:16px;left:16px;color:#fff;font-size:13px;font-weight:800;padding:6px 14px;border-radius:50px}
/* Info */
.prod-category{display:inline-flex;align-items:center;gap:6px;background:var(--gl);color:var(--gd);font-size:12px;font-weight:700;padding:4px 12px;border-radius:20px;text-decoration:none;margin-bottom:12px;border:1px solid var(--bd2);transition:all .2s}
.prod-category:hover{background:var(--g);color:#fff;border-color:var(--g)}
.prod-name{font-size:clamp(22px,3vw,32px);font-weight:900;color:var(--char);line-height:1.2;margin-bottom:12px}
.prod-rating{display:flex;align-items:center;gap:8px;margin-bottom:16px}
.stars-row{color:#FFE066;font-size:16px}
.rating-count{font-size:13px;color:var(--tx3)}
.sold-count{font-size:12px;color:var(--tx3);background:var(--gll);padding:3px 9px;border-radius:20px;border:1px solid var(--bd)}
.price-box{background:var(--gll);border-radius:14px;padding:16px 18px;border:1.5px solid var(--bd);margin-bottom:18px}
.price-main{font-size:32px;font-weight:900;color:var(--g);margin-bottom:4px}
.price-sub{display:flex;align-items:center;gap:10px}
.price-old{font-size:15px;color:var(--tx3);text-decoration:line-through}
.price-save{font-size:13px;font-weight:800;color:#fff;background:var(--pk);padding:3px 9px;border-radius:20px}
.price-discount{font-size:12px;color:var(--gd);margin-top:6px}
/* Specs */
.specs-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:18px}
.spec-item{background:#fff;border-radius:10px;padding:11px 14px;border:1px solid var(--bd)}
.spec-label{font-size:10px;font-weight:800;letter-spacing:1.5px;color:var(--tx3);text-transform:uppercase;margin-bottom:4px}
.spec-value{font-size:15px;font-weight:800;color:var(--char)}
/* Qty + Buttons */
.qty-label{font-size:13px;font-weight:700;color:var(--tx);margin-bottom:8px}
.qty-control{display:flex;align-items:center;border:1.5px solid var(--bd);border-radius:10px;overflow:hidden;width:fit-content;margin-bottom:16px}
.qty-btn{background:var(--gl);border:none;width:40px;height:44px;font-size:19px;cursor:pointer;font-weight:700;color:var(--gd)}
.qty-input{border:none;border-left:1.5px solid var(--bd);border-right:1.5px solid var(--bd);width:56px;height:44px;text-align:center;font-size:16px;font-weight:800;color:var(--char);font-family:'Be Vietnam Pro',sans-serif;outline:none;background:#fff}
.btn-order-main{width:100%;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;border:none;border-radius:12px;padding:15px;font-size:16px;font-weight:900;cursor:pointer;transition:all .25s;box-shadow:0 4px 18px rgba(107,191,31,.3);margin-bottom:10px}
.btn-order-main:hover{background:linear-gradient(135deg,#2E7D08,#5AAF15);transform:translateY(-2px);box-shadow:0 8px 26px rgba(107,191,31,.4)}
.btn-track{width:100%;background:#fff;color:var(--gd);border:1.5px solid var(--g);border-radius:12px;padding:13px;font-size:14px;font-weight:700;cursor:pointer;text-align:center;text-decoration:none;display:block;transition:all .2s}
.btn-track:hover{background:var(--gl)}
/* Trust badges */
.trust-row{display:flex;gap:12px;flex-wrap:wrap;margin-top:14px;padding-top:14px;border-top:1px solid var(--bd)}
.trust-badge{display:flex;align-items:center;gap:6px;font-size:12px;color:var(--tx2);font-weight:500}
/* Description */
.desc-box{background:#fff;border-radius:16px;padding:24px;border:1.5px solid var(--bd);margin-top:32px}
.desc-title{font-size:16px;font-weight:800;color:var(--char);margin-bottom:14px;padding-bottom:10px;border-bottom:1.5px solid var(--gl)}
.desc-content{font-size:14px;color:var(--tx2);line-height:1.8;white-space:pre-line}
/* Related */
.related-section{padding:0 5% 60px;max-width:1100px;margin:0 auto}
.section-title-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px}
.section-title{font-size:20px;font-weight:900;color:var(--char)}
.section-link{font-size:13px;color:var(--g);text-decoration:none;font-weight:700}
.related-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:18px}
.product-card{background:#fff;border-radius:16px;overflow:hidden;border:1.5px solid var(--bd);transition:transform .3s,box-shadow .3s}
.product-card:hover{transform:translateY(-8px);box-shadow:0 14px 40px rgba(58,122,10,.12)}
.product-img-link{display:block;position:relative;height:200px;overflow:hidden;text-decoration:none}
.product-img-link img{width:100%;height:100%;object-fit:cover;transition:transform .4s}
.product-card:hover .product-img-link img{transform:scale(1.06)}
.product-info{padding:13px}
.product-name{font-size:13px;font-weight:700;color:var(--char);margin-bottom:4px;line-height:1.4}
.product-size{font-size:11px;color:var(--tx3);margin-bottom:8px}
.product-price{font-size:17px;font-weight:900;color:var(--g);margin-bottom:10px}
.btn-buy{width:100%;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;border:none;border-radius:9px;padding:10px;font-size:13px;font-weight:700;cursor:pointer;transition:all .2s}
.btn-buy:hover{background:linear-gradient(135deg,#2E7D08,#5AAF15)}
footer{background:linear-gradient(175deg,#0F2E00,#1C5200);color:rgba(255,255,255,.7);padding:52px 5% 26px}
.footer-grid{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:36px;margin-bottom:40px}
.footer-logo{height:34px;filter:brightness(0) invert(1);display:block;margin-bottom:13px}
.footer-brand p{font-size:13px;line-height:1.9;margin-bottom:16px}
.social-links{display:flex;gap:10px}
.social-btn{width:35px;height:35px;border-radius:50%;background:rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;text-decoration:none;font-size:14px;transition:all .2s}
.social-btn:hover{background:var(--g)}
.footer-col h4{color:#fff;font-size:14px;font-weight:800;margin-bottom:16px}
.footer-col ul{list-style:none}
.footer-col li{margin-bottom:8px}
.footer-col a{color:rgba(255,255,255,.5);text-decoration:none;font-size:13px;transition:color .2s}
.footer-col a:hover{color:var(--gn)}
.footer-contact p{font-size:13px;margin-bottom:6px}
.footer-bottom{border-top:1px solid rgba(255,255,255,.08);padding-top:20px;display:flex;justify-content:space-between;flex-wrap:wrap;gap:10px;font-size:12px;color:rgba(255,255,255,.3)}

/* REVIEWS */
.reviews-section{margin-top:32px;background:#fff;border-radius:16px;padding:24px;border:1.5px solid var(--bd)}
.reviews-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;padding-bottom:14px;border-bottom:1.5px solid var(--gl)}
.reviews-title{font-size:17px;font-weight:900;color:var(--char)}
.avg-rating{display:flex;align-items:center;gap:10px}
.avg-stars{font-size:28px;font-weight:900;color:var(--g)}
.avg-stars-row{color:#FFE066;font-size:18px}
.avg-count{font-size:12px;color:var(--tx3)}
.review-item{padding:16px 0;border-bottom:1px solid var(--gl)}
.review-item:last-child{border-bottom:none}
.review-top{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:8px}
.review-author{display:flex;align-items:center;gap:10px}
.review-avatar{width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--gl),#CCEF90);border:2px solid var(--bd2);display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:900;color:var(--gd);flex-shrink:0}
.review-name{font-size:14px;font-weight:700;color:var(--char)}
.review-date{font-size:11px;color:var(--tx3)}
.review-stars{color:#FFE066;font-size:15px}
.review-content{font-size:13px;color:var(--tx2);line-height:1.7}
.review-badge{font-size:10px;font-weight:700;background:var(--gl);color:var(--gd);padding:2px 8px;border-radius:20px}
/* REVIEW FORM */
.review-form{background:var(--gll);border-radius:14px;padding:20px;margin-top:20px;border:1.5px solid var(--bd)}
.review-form-title{font-size:15px;font-weight:800;color:var(--char);margin-bottom:16px}
.star-picker{display:flex;gap:6px;margin-bottom:14px}
.star-btn{font-size:26px;cursor:pointer;color:#D1D5DB;transition:color .15s;background:none;border:none;padding:0}
.star-btn.active,.star-btn:hover{color:#FFE066}
.review-submit{width:100%;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;border:none;border-radius:10px;padding:13px;font-size:14px;font-weight:800;cursor:pointer;transition:all .2s;margin-top:6px}
.review-submit:hover{background:linear-gradient(135deg,#2E7D08,#5AAF15)}
.review-submit:disabled{background:#C8E89A;cursor:not-allowed}
.review-success{background:var(--gl);border-radius:10px;padding:14px;text-align:center;font-size:14px;font-weight:600;color:var(--gd);display:none}

/* MODAL */
.modal-overlay{position:fixed;inset:0;background:rgba(28,82,0,.55);z-index:999;display:none;align-items:center;justify-content:center;padding:16px;backdrop-filter:blur(5px)}
.modal-overlay.open{display:flex}
.modal{background:#fff;border-radius:22px;width:100%;max-width:570px;max-height:94vh;overflow-y:auto;position:relative;border:1.5px solid var(--bd)}
.modal::before{content:'';display:block;height:4px;background:linear-gradient(90deg,#3A9A12,var(--g),var(--gn),#FF8FB1,#A78BFA);border-radius:22px 22px 0 0}
.modal-header{padding:20px 26px 16px;border-bottom:1px solid var(--gl);position:sticky;top:0;background:#fff;z-index:2}
.modal-header h2{font-size:19px;font-weight:900;color:var(--char);margin-bottom:2px}
.modal-header p{font-size:12px;color:var(--tx3)}
.modal-close{position:absolute;top:16px;right:20px;background:var(--gl);border:none;width:32px;height:32px;border-radius:50%;font-size:16px;cursor:pointer;color:var(--gd);display:flex;align-items:center;justify-content:center}
.modal-state{padding:20px 26px;display:none}
.modal-state.active{display:block}
.order-product{display:flex;gap:13px;background:var(--gll);border-radius:12px;padding:13px;margin-bottom:16px;align-items:center;border:1px solid var(--bd)}
.order-product img{width:58px;height:58px;object-fit:cover;border-radius:9px;flex-shrink:0}
.op-name{font-weight:800;font-size:13px;color:var(--char);margin-bottom:2px}
.op-size{font-size:11px;color:var(--tx3);margin-bottom:3px}
.op-price{font-size:15px;font-weight:900;color:var(--g)}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px}
.form-row.single{grid-template-columns:1fr}
.form-group{display:flex;flex-direction:column;gap:5px}
.form-group label{font-size:12px;font-weight:700;color:var(--tx)}
.req{color:var(--pk)}
.form-group input,.form-group select,.form-group textarea{border:1.5px solid var(--bd);border-radius:9px;padding:10px 13px;font-size:13px;font-family:'Be Vietnam Pro',sans-serif;color:var(--tx);background:var(--gll);transition:border-color .2s;outline:none}
.form-group input:focus,.form-group select:focus,.form-group textarea:focus{border-color:var(--g);background:#fff}
.form-group textarea{resize:vertical;min-height:66px}
.qty-ctrl{display:flex;align-items:center;border:1.5px solid var(--bd);border-radius:9px;overflow:hidden;width:fit-content}
.qty-b{background:var(--gl);border:none;width:36px;height:40px;font-size:18px;cursor:pointer;font-weight:700;color:var(--gd)}
.qty-i{border:none;border-left:1.5px solid var(--bd);border-right:1.5px solid var(--bd);width:52px;height:40px;text-align:center;font-size:15px;font-weight:800;color:var(--char);font-family:'Be Vietnam Pro',sans-serif;outline:none;background:#fff}
.payment-opts{display:grid;grid-template-columns:1fr 1fr;gap:11px;margin-bottom:13px}
.payment-opt{border:2px solid var(--bd);border-radius:12px;padding:12px;cursor:pointer;display:flex;align-items:center;gap:9px;transition:all .2s;position:relative}
.payment-opt:hover{border-color:var(--g)}
.payment-opt.active{border-color:var(--g);background:var(--gl)}
.payment-opt input[type=radio]{display:none}
.payment-opt-icon{font-size:20px}
.payment-opt-text{font-size:13px;font-weight:700;color:var(--char);line-height:1.3}
.payment-opt-sub{font-size:11px;color:var(--tx3)}
.discount-badge{position:absolute;top:-8px;right:9px;background:var(--g);color:#fff;font-size:10px;font-weight:800;padding:2px 8px;border-radius:20px}
.order-summary{background:var(--gll);border-radius:12px;padding:15px;margin:13px 0;border:1px solid var(--bd)}
.summary-row{display:flex;justify-content:space-between;align-items:center;padding:5px 0;font-size:13px}
.summary-row.discount{color:var(--gd);font-weight:700}
.summary-row.total{border-top:1.5px dashed var(--bd);padding-top:11px;margin-top:4px;font-weight:800;font-size:16px}
.summary-row.total .val{color:var(--g);font-size:18px}
.btn-submit{width:100%;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;border:none;border-radius:12px;padding:14px;font-size:15px;font-weight:800;cursor:pointer;transition:all .2s;margin-top:5px}
.btn-submit:disabled{background:#C8E89A;cursor:not-allowed}
.qr-box{text-align:center;padding:6px 0}
.qr-frame{display:inline-block;background:#fff;border:3px solid var(--g);border-radius:16px;padding:12px;margin:14px auto;box-shadow:0 4px 18px rgba(107,191,31,.18)}
.qr-frame img{width:190px;height:190px;display:block;border-radius:7px}
.qr-amount{font-size:28px;font-weight:900;color:var(--g);margin-bottom:3px}
.qr-amount-label{font-size:12px;color:var(--tx3);margin-bottom:13px}
.bank-info{background:var(--gll);border-radius:12px;padding:13px;margin-bottom:14px;text-align:left;border:1px solid var(--bd)}
.bank-row{display:flex;justify-content:space-between;align-items:center;padding:5px 0;font-size:13px;border-bottom:1px dashed var(--bd)}
.bank-row:last-child{border-bottom:none}
.bank-row .label{color:var(--tx3);font-weight:500}
.bank-row .val{font-weight:800;color:var(--char)}
.copy-btn{background:var(--gl);color:var(--gd);border:1px solid var(--bd2);border-radius:6px;padding:3px 9px;font-size:11px;cursor:pointer;font-weight:700}
.copy-btn:hover{background:var(--g);color:#fff}
.countdown-wrap{background:var(--char);border-radius:12px;padding:12px;text-align:center;margin-bottom:14px;color:#fff}
.countdown-title{font-size:11px;opacity:.6;margin-bottom:3px}
.countdown-timer{font-size:26px;font-weight:900;color:var(--gn);letter-spacing:2px}
.countdown-sub{font-size:10px;opacity:.5;margin-top:3px}
.countdown-wrap.urgent{background:#991111}.countdown-wrap.urgent .countdown-timer{color:#ffcccc}
.btn-confirm-paid{width:100%;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;border:none;border-radius:12px;padding:13px;font-size:14px;font-weight:800;cursor:pointer;transition:all .2s}
.btn-confirm-paid:disabled{background:#C8E89A;cursor:not-allowed}
.btn-back-link{display:block;text-align:center;margin-top:10px;color:var(--tx3);font-size:12px;cursor:pointer;text-decoration:underline}
.waiting-box{text-align:center;padding:26px 13px}
.waiting-anim{font-size:50px;margin-bottom:13px;display:block;animation:pulse 1.5s ease-in-out infinite}
@keyframes pulse{0%,100%{transform:scale(1)}50%{transform:scale(1.1);opacity:.7}}
.waiting-box h3{font-size:19px;font-weight:900;color:var(--char);margin-bottom:8px}
.waiting-box p{font-size:13px;color:var(--tx2);line-height:1.7;margin-bottom:5px}
.order-code-display{font-size:19px;font-weight:900;background:#EEF8FF;color:#74C7FF;padding:8px 20px;border-radius:9px;display:inline-block;margin:11px 0}
.waiting-steps{background:var(--gll);border-radius:12px;padding:13px;margin:12px 0;text-align:left;border:1px solid var(--bd)}
.ws-item{display:flex;align-items:center;gap:8px;padding:6px 0;font-size:12px}
.success-box{text-align:center;padding:30px 16px}
.success-icon-big{font-size:58px;margin-bottom:13px;display:block}
.success-box h3{font-size:22px;font-weight:900;color:var(--g);margin-bottom:8px}
.success-box p{font-size:13px;color:var(--tx2);line-height:1.7;margin-bottom:5px}
.order-code-success{font-size:18px;font-weight:900;background:var(--gl);color:var(--gd);padding:8px 20px;border-radius:9px;display:inline-block;margin:11px 0;border:1.5px solid var(--bd2)}
.btn-close-modal{background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;border:none;border-radius:50px;padding:11px 28px;font-size:14px;font-weight:800;cursor:pointer;margin-top:12px}
.toast{position:fixed;bottom:22px;left:50%;transform:translateX(-50%) translateY(100px);background:var(--char);color:#fff;padding:11px 22px;border-radius:50px;font-size:13px;font-weight:600;z-index:9999;transition:transform .4s;white-space:nowrap;max-width:90vw;text-align:center}
.toast.show{transform:translateX(-50%) translateY(0)}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.2}}
@media(max-width:900px){.detail-grid{grid-template-columns:1fr}.footer-grid{grid-template-columns:1fr 1fr}.nav-links{display:none}.nav-hamburger{display:flex}}
@media(max-width:600px){.specs-grid{grid-template-columns:1fr 1fr}.footer-grid{grid-template-columns:1fr}.footer-bottom{flex-direction:column;text-align:center}.form-row{grid-template-columns:1fr}.payment-opts{grid-template-columns:1fr}.nav-phone,.nav-tracuu{display:none}nav{padding:0 4%}.breadcrumb{padding:12px 4%;flex-wrap:wrap}.product-detail{padding:20px 4% 40px}.size-opt{flex:1;min-width:calc(50% - 4px)!important}}
</style>
</head>
<body>
<nav id="mainNav">
  <a href="{{ route('home') }}"><img src="{{ asset('images/logo_dali.png') }}" alt="DALI" class="nav-logo"></a>
  <ul class="nav-links">
    <li><a href="{{ route('home') }}">Trang chủ</a></li>
    <li><a href="{{ route('products') }}" class="active">Sản phẩm</a></li>
    <li><a href="{{ route('home') }}#ve-chung-toi">Về chúng tôi</a></li>
    <li><a href="{{ route('home') }}#huong-dan">Hướng dẫn</a></li>
    <li><a href="{{ route('home') }}#lien-he">Liên hệ</a></li>
  </ul>
  <div class="nav-right">
    <a href="tel:{{ $settings['shop_phone'] ?? '0123456789' }}" class="nav-phone"><i class="ri-phone-line" style="margin-right:5px"></i>{{ $settings['shop_phone'] ?? '0123456789' }}</a>
    <a href="{{ route('track-order') }}" class="nav-tracuu" style="font-size:13px;color:rgba(255,255,255,.75);text-decoration:none;font-weight:500"><i class="ri-search-line" style="margin-right:5px"></i>Tra cứu đơn</a>
    <a href="#" class="btn-order-nav" onclick="openOrderDetail();return false">Đặt hàng</a>
    <button class="nav-hamburger" id="hamburger" onclick="toggleMenu()"><span></span><span></span><span></span></button>
  </div>
</nav>
<div class="mobile-nav" id="mobileNav">
  <a href="{{ route('home') }}"><i class="ri-home-5-line"></i> Trang chủ</a>
  <a href="{{ route('products') }}"><i class="ri-palette-line"></i> Sản phẩm</a>
  <a href="{{ route('track-order') }}"><i class="ri-search-line"></i> Tra cứu đơn hàng</a>
</div>
<div class="sakura-strip"><span class="petal"><i class="ri-flower-line"></i></span><span class="petal"><i class="ri-flower-line"></i></span><span class="petal"><i class="ri-leaf-line"></i></span><span class="petal"><i class="ri-flower-line"></i></span><span class="sak-text">DALI · TÔ ĐIỂM CUỘC SỐNG</span></div>

{{-- Breadcrumb --}}
<div class="breadcrumb">
  <a href="{{ route('home') }}">Trang chủ</a>
  <span class="breadcrumb-sep">›</span>
  <a href="{{ route('products') }}">Sản phẩm</a>
  @if($product->category)
  <span class="breadcrumb-sep">›</span>
  <a href="{{ route('products') }}?category={{ $product->category->slug }}">{{ $product->category->name }}</a>
  @endif
  <span class="breadcrumb-sep">›</span>
  <span style="color:var(--tx)">{{ $product->name }}</span>
</div>

{{-- Product Detail --}}
<div class="product-detail">
  <div class="detail-grid">
    {{-- Ảnh --}}
    <div>
      <div class="prod-img-box">
        @if($product->main_image)
          <img src="{{ asset('storage/'.$product->main_image) }}" alt="{{ $product->name }}" id="mainImg">
        @else
          <img src="https://images.unsplash.com/photo-1578926288207-a90a5366759d?w=700&q=80" alt="{{ $product->name }}" id="mainImg">
        @endif
        @if($product->badge)
          <div class="prod-badge" style="background:{{ $product->badge_type=='new' ? '#2563EB' : ($product->badge_type=='hot' ? '#E11D48' : ($product->badge_type=='sale' ? '#D97706' : 'var(--g)')) }}">{{ $product->badge }}</div>
        @endif
      </div>
    </div>

    {{-- Info --}}
    <div>
      @if($product->category)
      <a href="{{ route('products') }}?category={{ $product->category->slug }}" class="prod-category">
        {{ $product->category->icon }} {{ $product->category->name }}
      </a>
      @endif

      <h1 class="prod-name">{{ $product->name }}</h1>

      @php $rvCount = $product->reviews->count(); @endphp
      <div class="prod-rating">
        @if($rvCount > 0)
        <div class="stars-row">{{ str_repeat('★', round($product->avg_rating)) }}{{ str_repeat('☆', 5-round($product->avg_rating)) }}</div>
        <span class="rating-count">{{ $product->avg_rating }}/5 · {{ $rvCount }} đánh giá</span>
        @else
        <span class="rating-count" style="color:var(--tx3)">Chưa có đánh giá</span>
        @endif
        @if($product->sold_count > 0)<span class="sold-count">Đã bán {{ $product->sold_count }}</span>@endif
      </div>

      @php $psizes = $product->sizes(); $firstSize = $psizes->first(); @endphp
      <div class="price-box">
        <div class="price-main" id="detailPrice">{{ $firstSize ? $firstSize->display_price : $product->display_price }}</div>
        <div class="price-discount" style="margin-top:8px;font-size:12px;color:var(--gd)"><i class="ri-bank-card-line"></i> Chuyển khoản QR → giảm thêm {{ (int)($settings['discount_bank'] ?? 5) }}%</div>
      </div>

      @if($psizes->count())
      <div style="margin-bottom:18px">
        <div class="qty-label">Chọn kích thước <span style="color:var(--pk)">*</span></div>
        <div id="sizeOptions" style="display:flex;flex-wrap:wrap;gap:8px">
          @foreach($psizes as $s)
          <button type="button" class="size-opt" data-id="{{ $s->id }}" data-price="{{ $s->price }}" data-label="{{ $s->label }}" data-pricestr="{{ $s->display_price }}" onclick="selectSize(this)"
            style="padding:9px 13px;border:1.5px solid var(--bd);border-radius:10px;background:#fff;cursor:pointer;font-family:inherit;text-align:left;transition:all .15s;min-width:94px">
            <span style="font-size:13px;font-weight:700;color:var(--char)">{{ $s->name }}</span>@if($s->note)<span style="font-size:10px;color:var(--pk)"> · {{ $s->note }}</span>@endif
            <span style="display:block;font-size:12px;color:var(--g);font-weight:800;margin-top:2px">{{ $s->display_price }}</span>
          </button>
          @endforeach
        </div>
      </div>
      @endif

      {{-- Specs --}}
      @if($product->colors_count)
      <div class="specs-grid">
        <div class="spec-item">
          <div class="spec-label">Số màu</div>
          <div class="spec-value">{{ $product->colors_count }} màu</div>
        </div>
      </div>
      @endif

      {{-- Qty --}}
      <div class="qty-label">Số lượng</div>
      <div class="qty-control" id="detailQtyWrap">
        <button class="qty-btn" onclick="changeDetailQty(-1)">−</button>
        <input class="qty-input" id="detailQty" type="number" value="1" min="1" max="99">
        <button class="qty-btn" onclick="changeDetailQty(1)">+</button>
      </div>

      <button class="btn-order-main" onclick="openOrderDetail()"><i class="ri-shopping-cart-2-line"></i> Đặt mua ngay</button>
      <button class="btn-track" style="cursor:pointer;margin-bottom:10px" onclick="addDetailToCart()"><i class="ri-add-line"></i> Thêm vào giỏ hàng</button>
      <a href="{{ route('track-order') }}" class="btn-track"><i class="ri-search-line"></i> Tra cứu đơn hàng đã đặt</a>

      <div class="trust-row">
        <div class="trust-badge"><i class="ri-truck-line"></i> Miễn phí ship từ 299K</div>
        <div class="trust-badge"><i class="ri-lock-line"></i> Bảo mật 100%</div>
      </div>
    </div>
  </div>

  {{-- Description --}}
  @if($product->description)
  <div class="desc-box">
    <div class="desc-title"><i class="ri-book-open-line"></i> Mô tả sản phẩm</div>
    <div class="desc-content">{{ $product->description }}</div>
  </div>
  @endif
</div>

{{-- Related Products --}}
@if($related->count())
<div class="related-section">
  <div class="section-title-row">
    <div class="section-title">Sản phẩm liên quan</div>
    <a href="{{ route('products') }}?category={{ $product->category->slug ?? '' }}" class="section-link">Xem thêm →</a>
  </div>
  <div class="related-grid">
    @foreach($related as $r)
    <div class="product-card">
      <a href="{{ route('product', $r->slug) }}" class="product-img-link">
        @if($r->main_image)
          <img src="{{ asset('storage/'.$r->main_image) }}" alt="{{ $r->name }}" loading="lazy">
        @else
          <img src="https://images.unsplash.com/photo-1578926288207-a90a5366759d?w=400&q=60" alt="{{ $r->name }}" loading="lazy">
        @endif
      </a>
      <div class="product-info">
        <a href="{{ route('product', $r->slug) }}" class="product-name" style="text-decoration:none;color:inherit;display:block">{{ $r->name }}</a>
        <div class="product-size">{{ $r->colors_count ? $r->colors_count.' màu' : '' }}</div>
        <div class="product-price">@if($r->has_multiple_sizes)<span style="font-size:11px;color:var(--tx3);font-weight:600">Từ </span>@endif{{ $r->display_price }}</div>
        <button class="btn-buy" onclick="window.location='{{ route('product', $r->slug) }}'"><i class="ri-shopping-cart-2-line"></i> Đặt mua</button>
      </div>
    </div>
    @endforeach
  </div>
</div>
@endif

{{-- ── REVIEWS SECTION ── --}}
<div class="product-detail" style="padding-top:0">
  <div class="reviews-section">
    <div class="reviews-header">
      <div class="reviews-title">⭐ Đánh giá từ khách hàng</div>
      <div class="avg-rating">
        <div class="avg-stars">{{ $product->avg_rating }}</div>
        <div>
          <div class="avg-stars-row">{{ str_repeat('★', round($product->avg_rating)) }}{{ str_repeat('☆', 5-round($product->avg_rating)) }}</div>
          <div class="avg-count">{{ $product->reviews->count() }} đánh giá</div>
        </div>
      </div>
    </div>

    {{-- Existing reviews --}}
    @forelse($product->reviews as $rv)
    <div class="review-item">
      <div class="review-top">
        <div class="review-author">
          <div class="review-avatar">{{ mb_substr($rv->customer_name,0,1) }}</div>
          <div>
            <div class="review-name">{{ $rv->customer_name }}</div>
            <div class="review-date">{{ $rv->created_at->diffForHumans() }}</div>
          </div>
        </div>
        <div>
          <div class="review-stars">{{ $rv->stars }}</div>
          @if($rv->order_code)<div class="review-badge">✓ Đã mua</div>@endif
        </div>
      </div>
      @if($rv->content)<div class="review-content">{{ $rv->content }}</div>@endif
      @if($rv->image)
      <div style="margin-top:10px">
        <img src="{{ asset('storage/'.$rv->image) }}" alt="Thành quả của {{ $rv->customer_name }}" loading="lazy"
             style="max-width:180px;max-height:180px;border-radius:10px;border:1.5px solid var(--bd);cursor:zoom-in;object-fit:cover"
             onclick="window.open(this.src,'_blank')">
      </div>
      @endif
    </div>
    @empty
    <div style="text-align:center;padding:28px;color:var(--tx3);font-size:14px">
      Chưa có đánh giá nào. Hãy là người đầu tiên đánh giá!
    </div>
    @endforelse

    {{-- Write review form --}}
    <div class="review-form" id="reviewForm">
      <div class="review-form-title"><i class="ri-edit-line"></i> Đánh giá &amp; khoe thành quả của bạn</div>
      <div class="review-success" id="reviewSuccess">🎉 Cảm ơn! Đánh giá của bạn đang chờ admin duyệt.</div>
      <div id="reviewFormContent">
        <div style="margin-bottom:12px">
          <label style="font-size:12px;font-weight:700;color:var(--tx);display:block;margin-bottom:8px">Đánh giá <span style="color:var(--pk)">*</span></label>
          <div class="star-picker" id="starPicker">
            @for($i=1;$i<=5;$i++)
            <button type="button" class="star-btn" data-val="{{ $i }}" onclick="setStar({{ $i }})">★</button>
            @endfor
          </div>
          <input type="hidden" id="ratingVal" value="5">
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px">
          <div>
            <label style="font-size:12px;font-weight:700;color:var(--tx);display:block;margin-bottom:6px">Họ tên <span style="color:var(--pk)">*</span></label>
            <input type="text" id="rvName" placeholder="Nguyễn Văn A" style="width:100%;background:var(--gll);border:1.5px solid var(--bd);border-radius:9px;padding:10px 13px;font-size:13px;font-family:'Be Vietnam Pro',sans-serif;color:var(--tx);outline:none">
          </div>
          <div>
            <label style="font-size:12px;font-weight:700;color:var(--tx);display:block;margin-bottom:6px">Số điện thoại</label>
            <input type="tel" id="rvPhone" placeholder="0912 345 678" style="width:100%;background:var(--gll);border:1.5px solid var(--bd);border-radius:9px;padding:10px 13px;font-size:13px;font-family:'Be Vietnam Pro',sans-serif;color:var(--tx);outline:none">
          </div>
        </div>
        <div style="margin-bottom:12px">
          <label style="font-size:12px;font-weight:700;color:var(--tx);display:block;margin-bottom:6px">Mã đơn hàng <span style="font-size:10px;color:var(--tx3);font-weight:400">(không bắt buộc)</span></label>
          <input type="text" id="rvOrder" placeholder="DALI-XXXXXX" style="width:100%;background:var(--gll);border:1.5px solid var(--bd);border-radius:9px;padding:10px 13px;font-size:13px;font-family:'Be Vietnam Pro',sans-serif;color:var(--tx);outline:none;text-transform:uppercase">
        </div>
        <div style="margin-bottom:12px">
          <label style="font-size:12px;font-weight:700;color:var(--tx);display:block;margin-bottom:6px">Nội dung đánh giá</label>
          <textarea id="rvContent" placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm..." rows="3" style="width:100%;background:var(--gll);border:1.5px solid var(--bd);border-radius:9px;padding:10px 13px;font-size:13px;font-family:'Be Vietnam Pro',sans-serif;color:var(--tx);outline:none;resize:vertical"></textarea>
        </div>
        <div style="margin-bottom:12px">
          <label style="font-size:12px;font-weight:700;color:var(--tx);display:block;margin-bottom:6px"><i class="ri-camera-line"></i> Khoe thành quả <span style="font-size:10px;color:var(--tx3);font-weight:400">(ảnh tranh bạn đã tô — không bắt buộc)</span></label>
          <input type="file" id="rvImage" accept="image/*" style="width:100%;font-size:12px;color:var(--tx2)">
          <div id="rvImgPreview" style="margin-top:8px"></div>
        </div>
        <button class="review-submit" id="rvSubmitBtn" onclick="submitReview()">Gửi đánh giá →</button>
      </div>
    </div>
  </div>
</div>

<script>
// ── STAR RATING ──
function setStar(val){
  document.getElementById('ratingVal').value=val;
  document.querySelectorAll('.star-btn').forEach(function(b){
    b.classList.toggle('active', parseInt(b.dataset.val)<=val);
  });
}
setStar(5);

// ── PREVIEW ẢNH THÀNH QUẢ ──
(function(){var el=document.getElementById('rvImage');if(!el)return;el.addEventListener('change',function(){var p=document.getElementById('rvImgPreview');if(this.files&&this.files[0]){var r=new FileReader();r.onload=function(e){p.innerHTML='<img src="'+e.target.result+'" style="max-width:120px;max-height:120px;border-radius:8px;border:1.5px solid var(--bd);object-fit:cover">';};r.readAsDataURL(this.files[0]);}else{p.innerHTML='';}});})();

// ── SUBMIT REVIEW ──
async function submitReview(){
  var name=document.getElementById('rvName').value.trim();
  var rating=parseInt(document.getElementById('ratingVal').value)||5;
  if(!name){showToast('⚠️ Vui lòng nhập họ tên');return;}
  var btn=document.getElementById('rvSubmitBtn');
  btn.disabled=true; btn.textContent='⏳ Đang gửi...';
  try{
    var fd=new FormData();
    fd.append('product_id','{{ $product->id }}');
    fd.append('customer_name',name);
    fd.append('customer_phone',document.getElementById('rvPhone').value.trim());
    fd.append('rating',rating);
    fd.append('content',document.getElementById('rvContent').value.trim());
    fd.append('order_code',document.getElementById('rvOrder').value.trim().toUpperCase());
    var imgEl=document.getElementById('rvImage');
    if(imgEl && imgEl.files && imgEl.files[0]) fd.append('image',imgEl.files[0]);
    var res=await fetch('{{ route("submit-review") }}',{
      method:'POST',
      headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'},
      body:fd
    });
    var d=await res.json();
    if(d.success){
      document.getElementById('reviewFormContent').style.display='none';
      document.getElementById('reviewSuccess').style.display='block';
      showToast('✅ '+d.message);
    }else showToast('❌ '+(d.message||'Có lỗi xảy ra'));
  }catch(e){showToast('❌ Lỗi kết nối');}
  btn.disabled=false; btn.textContent='Gửi đánh giá →';
}
</script>

<footer id="lien-he">
  <div class="footer-grid">
    <div class="footer-brand"><img src="{{ asset('images/logo_dali.png') }}" alt="DALI" class="footer-logo"><p>DALI – Thương hiệu tranh tô màu số hóa hàng đầu Việt Nam.</p><div class="social-links"><a href="https://www.facebook.com/tranhtomau.dali" target="_blank" rel="noopener" class="social-btn" aria-label="Facebook"><i class="ri-facebook-circle-line"></i></a><a href="https://m.me/tranhtomau.dali" target="_blank" rel="noopener" class="social-btn" aria-label="Messenger"><i class="ri-messenger-line"></i></a></div></div>
    <div class="footer-col"><h4>Sản phẩm</h4><ul><li><a href="{{ route('products') }}">Xem tất cả</a></li></ul></div>
    <div class="footer-col"><h4>Hỗ trợ</h4><ul><li><a href="{{ route('track-order') }}">Tra cứu đơn hàng</a></li><li><a href="#">Chính sách đổi trả</a></li></ul></div>
    <div class="footer-col"><h4>Liên hệ</h4><p><i class="ri-phone-line"></i> <a href="tel:{{ $settings['shop_phone'] ?? '' }}" style="color:var(--gn);text-decoration:none">{{ $settings['shop_phone'] ?? '0123456789' }}</a></p><p><i class="ri-time-line"></i> T2–T7: 8:00 – 20:00</p></div>
  </div>
  <div class="footer-bottom"><span>© 2024 DALI Tranh Tô Màu Số Hóa</span><span>Thiết kế tại Việt Nam 🇻🇳</span></div>
</footer>

{{-- Modal --}}
<div class="modal-overlay" id="orderModal" onclick="if(event.target===this)closeOrder()">
  <div class="modal">
    <div class="modal-header"><h2 id="modalTitle"><i class="ri-shopping-cart-2-line"></i> Đặt hàng DALI</h2><p id="modalSub">Điền thông tin – xác nhận trong 30 phút</p><button class="modal-close" onclick="closeOrder()">✕</button></div>
    <div class="modal-state active" id="state-form">
      <div class="order-product"><img id="oImg" src="" alt="" onerror="this.style.display='none'"><div><div class="op-name" id="oName">–</div><div class="op-size" id="oSize">–</div><div class="op-price" id="oPrice">–</div></div></div>
      <div style="margin-bottom:13px"><label style="font-size:12px;font-weight:700;color:var(--tx);display:block;margin-bottom:7px">Số lượng</label><div class="qty-ctrl"><button class="qty-b" onclick="changeQty(-1)">−</button><input class="qty-i" id="qtyInput" type="number" value="1" min="1" max="99"><button class="qty-b" onclick="changeQty(1)">+</button></div></div>
      <div class="form-row"><div class="form-group"><label>Họ và tên <span class="req">*</span></label><input type="text" id="custName" placeholder="Nguyễn Văn A"></div><div class="form-group"><label>Số điện thoại <span class="req">*</span></label><input type="tel" id="custPhone" placeholder="0912 345 678"></div></div>
      <div class="form-row single"><div class="form-group"><label>Tỉnh / Thành phố <span class="req">*</span></label><select id="custCity"><option value="">— Chọn tỉnh/thành —</option><option>Hà Nội</option><option>TP. Hồ Chí Minh</option><option>Đà Nẵng</option><option>Hải Phòng</option><option>Cần Thơ</option><option>Đồng Nai</option><option>Bình Dương</option><option>An Giang</option><option>Khánh Hòa</option><option>Nghệ An</option><option>Thanh Hóa</option><option>Thừa Thiên Huế</option><option>Quảng Ninh</option><option>Lâm Đồng</option><option>Long An</option><option>Tiền Giang</option><option>Kiên Giang</option><option>Đắk Lắk</option><option>Nam Định</option><option>Ninh Bình</option><option>Hải Dương</option><option>Bắc Giang</option><option>Bắc Ninh</option><option>Phú Thọ</option><option>Vĩnh Phúc</option><option>Hà Tĩnh</option><option>Quảng Nam</option><option>Bình Thuận</option><option>Yên Bái</option></select></div></div>
      <div class="form-row single"><div class="form-group"><label>Địa chỉ cụ thể <span class="req">*</span></label><input type="text" id="custAddr" placeholder="Số nhà, tên đường, phường/xã, quận/huyện"></div></div>
      <div class="form-row single"><div class="form-group"><label>Ghi chú</label><textarea id="custNote" placeholder="Gọi trước khi giao..."></textarea></div></div>
      <div class="form-row single"><div class="form-group"><label>Email <span style="font-size:10px;color:var(--tx3);font-weight:400">(nhận xác nhận đơn hàng)</span></label><input type="email" id="custEmail" placeholder="email@gmail.com" style="width:100%;background:var(--gll);border:1.5px solid var(--bd);border-radius:9px;padding:10px 13px;font-size:13px;font-family:'Be Vietnam Pro',sans-serif;color:var(--tx);outline:none"></div></div>
<label style="font-size:12px;font-weight:700;color:var(--tx);display:block;margin-bottom:10px">Hình thức thanh toán <span class="req">*</span></label>
      <div class="payment-opts">
        <label class="payment-opt" id="pay-cod" onclick="selectPay('COD')"><input type="radio" name="payment" value="COD"><span class="payment-opt-icon"><i class="ri-money-dollar-circle-line"></i></span><div><div class="payment-opt-text">COD</div><div class="payment-opt-sub">Trả khi nhận hàng</div></div></label>
        <label class="payment-opt active" id="pay-bank" onclick="selectPay('BANK')"><input type="radio" name="payment" value="BANK" checked><span class="payment-opt-icon"><i class="ri-smartphone-line"></i></span><div><div class="payment-opt-text">QR Chuyển khoản</div><div class="payment-opt-sub">Giảm {{ (int)($settings['discount_bank'] ?? 5) }}% ngay</div></div><div class="discount-badge">-{{ (int)($settings['discount_bank'] ?? 5) }}%</div></label>
      </div>
      <div class="order-summary">
        <div class="summary-row"><span>Giá sản phẩm</span><span id="sumPrice">–</span></div>
        <div class="summary-row"><span>Số lượng</span><span id="sumQty">1</span></div>
        <div class="summary-row discount" id="discountRow" style="display:none"><span>🎉 Giảm {{ (int)($settings['discount_bank'] ?? 5) }}% chuyển khoản</span><span id="sumDiscount">–</span></div>
        <div class="summary-row discount" id="couponDiscRow" style="display:none"><span><i class="ri-price-tag-3-line"></i> Mã giảm giá</span><span id="sumCouponDiscount">–</span></div>
<div class="summary-row"><span>Phí giao hàng</span><span id="sumShip" style="color:var(--g)">Miễn phí</span></div>
        <div class="summary-row total"><span>Tổng thanh toán</span><span class="val" id="sumTotal">–</span></div>
      </div>
      <button class="btn-submit" id="submitBtn" onclick="handleSubmit()"><span id="submitText">Tiếp theo →</span></button>
      <p style="text-align:center;font-size:11px;color:var(--tx3);margin-top:9px"><i class="ri-lock-line"></i> Thông tin của bạn được bảo mật</p>
    </div>
    <div class="modal-state" id="state-qr">
      <div class="qr-box">
        <div style="background:var(--gl);border-radius:12px;padding:12px 15px;margin-bottom:16px;display:flex;align-items:center;gap:10px;border:1px solid var(--bd2)"><span style="font-size:20px;flex-shrink:0"><i class="ri-bank-card-line"></i></span><div><strong style="font-size:13px;display:block;margin-bottom:2px">Quét mã QR để thanh toán</strong><p style="font-size:12px;color:var(--gd);line-height:1.5">Nội dung CK <b>phải ghi đúng mã đơn</b></p></div></div>
        <div class="qr-frame"><img id="qrImg" src="" alt="QR"></div>
        <div class="qr-amount" id="qrAmount">–</div>
        <div class="qr-amount-label">Số tiền cần chuyển (đã giảm {{ (int)($settings['discount_bank'] ?? 5) }}%)</div>
        <div class="bank-info">
          <div class="bank-row"><span class="label">Ngân hàng</span><span class="val" id="bi-bank">–</span></div>
          <div class="bank-row"><span class="label">Số tài khoản</span><span class="val" id="bi-acc">–</span><button class="copy-btn" onclick="copyText('bi-acc')">Sao chép</button></div>
          <div class="bank-row"><span class="label">Chủ tài khoản</span><span class="val" id="bi-name">–</span></div>
          <div class="bank-row"><span class="label">Số tiền</span><span class="val" id="bi-amount">–</span><button class="copy-btn" onclick="copyAmountRaw()">Sao chép</button></div>
          <div class="bank-row"><span class="label">Nội dung CK</span><span class="val" id="bi-note">–</span><button class="copy-btn" onclick="copyText('bi-note')">Sao chép</button></div>
        </div>
        <div class="countdown-wrap" id="countdownBox"><div class="countdown-title"><i class="ri-timer-line"></i> Thời gian giữ đơn hàng</div><div class="countdown-timer" id="countdownDisplay">15:00</div><div class="countdown-sub">Đơn sẽ huỷ nếu quá thời gian</div></div>
        <button class="btn-confirm-paid" id="paidBtn" onclick="customerConfirmPaid()"><i class="ri-checkbox-circle-line"></i> Tôi đã chuyển khoản xong</button>
        <span class="btn-back-link" onclick="showState('form')">← Quay lại chỉnh sửa đơn hàng</span>
      </div>
    </div>
    <div class="modal-state" id="state-waiting">
      <div class="waiting-box">
        <span class="waiting-anim">⏳</span><h3>Đang chờ xác nhận thanh toán</h3>
        <p>Chúng tôi đã nhận đơn hàng của bạn.</p>
        <div class="order-code-display" id="waitOrderCode">DALI-000000</div>
        <div class="waiting-steps"><div class="ws-item"><i class="ri-checkbox-circle-line"></i> Khách xác nhận đã chuyển khoản</div><div class="ws-item"><i class="ri-search-line"></i> Shop đang kiểm tra tài khoản</div><div class="ws-item"><i class="ri-box-3-line"></i> Xác nhận → đóng gói & giao hàng</div></div>
        <p style="font-size:12px;color:var(--g);font-weight:700">Thường xác nhận trong 15–30 phút</p>
        <button class="btn-close-modal" onclick="closeOrder()">Đóng & tiếp tục mua sắm</button>
      </div>
    </div>
    <div class="modal-state" id="state-success">
      <div class="success-box">
        <span class="success-icon-big">🎉</span><h3>Đặt hàng thành công!</h3>
        <p>Cảm ơn bạn đã tin tưởng DALI.<br>Chúng tôi sẽ liên hệ xác nhận trong vòng <strong>30 phút</strong>.</p>
        <div class="order-code-success" id="successCode">DALI-000000</div>
        <p style="font-size:12px;color:var(--tx3)">Lưu mã để <a href="{{ route('track-order') }}" style="color:var(--g)">tra cứu đơn hàng</a></p>
        <button class="btn-close-modal" onclick="closeOrder()">Tiếp tục mua sắm</button>
      </div>
    </div>
  </div>
</div>
<div class="toast" id="toast"></div>

<script>
var gOrder={},gCountdown=null,payMode='BANK';
var liveShip=null, FREE_SHIP={{ (int)($settings['free_ship_from'] ?? 299000) }}, FLAT_SHIP={{ (int)($settings['ship_fee'] ?? 30000) }};
var DISCOUNT_PCT={{ (int)($settings['discount_bank'] ?? 5) }};
var CFG_BANK_ID='{{ $settings["bank_id"] ?? "VCB" }}';
var CFG_BANK_ACC='{{ $settings["bank_acc"] ?? "" }}';
var CFG_BANK_NAME='{{ $settings["bank_name"] ?? "" }}';
var CFG_BANK_LABEL='{{ $settings["bank_label"] ?? "Ngân hàng" }}';
function fmtVnd(n){return Math.round(n).toLocaleString('vi-VN')+'đ';}
function parseVnd(s){return parseInt((s||'').replace(/[^\d]/g,''))||0;}

var SELECTED_SIZE=null;
function selectSize(btn){
  document.querySelectorAll('.size-opt').forEach(function(b){b.style.borderColor='var(--bd)';b.style.background='#fff';});
  btn.style.borderColor='var(--g)';btn.style.background='var(--gll)';
  SELECTED_SIZE={id:btn.dataset.id,price:parseInt(btn.dataset.price)||0,label:btn.dataset.label,priceStr:btn.dataset.pricestr};
  var pm=document.getElementById('detailPrice');if(pm)pm.textContent=btn.dataset.pricestr;
}
// Tự chọn kích thước đầu tiên (nhỏ nhất) khi tải trang
document.addEventListener('DOMContentLoaded',function(){var f=document.querySelector('.size-opt');if(f)selectSize(f);});

function openOrderDetail(){
  var qty=parseInt(document.getElementById('detailQty').value)||1;
  var colors='{{ $product->colors_count ? $product->colors_count." màu" : "" }}';
  var sizeLabel = SELECTED_SIZE ? SELECTED_SIZE.label : '';
  var priceStr  = SELECTED_SIZE ? SELECTED_SIZE.priceStr : '{{ $firstSize ? $firstSize->display_price : $product->display_price }}';
  var sub = (sizeLabel ? sizeLabel : '') + (sizeLabel && colors ? ' · ' : '') + colors;
  openOrder(@js($product->name), sub, priceStr, '{{ $product->main_image ? asset("storage/".$product->main_image) : "" }}', {{ $product->id }}, qty);
}
async function addDetailToCart(){
  var qty=parseInt(document.getElementById('detailQty').value)||1;
  var sizeId = SELECTED_SIZE ? SELECTED_SIZE.id : 0;
  try{
    var res=await fetch('{{ route("cart.add") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({product_id:{{ $product->id }},size_id:sizeId,quantity:qty})});
    var d=await res.json();
    if(d.success){
      var badge=document.getElementById('cartCount'); if(badge) badge.textContent=d.count;
      showToast('✅ Đã thêm vào giỏ! Bấm 🛒 để xem giỏ hàng');
    } else showToast('❌ '+(d.message||'Có lỗi'));
  }catch(e){showToast('❌ Lỗi kết nối');}
}
function openOrder(name,size,price,img,productId,qty){
  gOrder={name:name,size:size,price:parseVnd(price),priceStr:price,img:img||'',productId:productId||null};
  document.getElementById('oImg').src=img||'';
  document.getElementById('oName').textContent=name;
  document.getElementById('oSize').textContent=size;
  document.getElementById('oPrice').textContent=price;
  document.getElementById('qtyInput').value=qty||1;
  selectPay('BANK');updateSummary();showState('form');
  document.getElementById('orderModal').classList.add('open');
  document.body.style.overflow='hidden';
  document.getElementById('modalTitle').textContent='🛒 Đặt hàng DALI';
  document.getElementById('modalSub').textContent='Điền thông tin – xác nhận trong 30 phút';
  return false;
}
function closeOrder(){document.getElementById('orderModal').classList.remove('open');document.body.style.overflow='';if(gCountdown){clearInterval(gCountdown);gCountdown=null;}}
function showState(s){document.querySelectorAll('.modal-state').forEach(e=>e.classList.remove('active'));document.getElementById('state-'+s).classList.add('active');}
function selectPay(mode){payMode=mode;document.getElementById('pay-cod').classList.toggle('active',mode==='COD');document.getElementById('pay-bank').classList.toggle('active',mode==='BANK');updateSummary();refreshShip();}
async function refreshShip(){
  var city=(document.getElementById('custCity')||{}).value||'';
  var addr=((document.getElementById('custAddr')||{}).value||'').trim();
  var qty=parseInt(document.getElementById('qtyInput').value)||1;
  var sub=gOrder.price*qty;var disc=payMode==='BANK'?Math.round(sub*DISCOUNT_PCT/100):0;var after=sub-disc;
  if(after>=FREE_SHIP||!city||!addr){liveShip=null;updateSummary();return;}
  var el=document.getElementById('sumShip');if(el)el.textContent='Đang tính...';
  try{
    var res=await fetch('{{ route("calc-ship") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({amount:after,qty:qty,city:city,address:addr,payment:payMode,size_id:(typeof SELECTED_SIZE!=='undefined'&&SELECTED_SIZE?SELECTED_SIZE.id:0)})});
    var d=await res.json();liveShip=(d&&typeof d.fee==='number')?d.fee:null;
  }catch(e){liveShip=null;}
  updateSummary();
}
document.addEventListener('DOMContentLoaded',function(){
  var c=document.getElementById('custCity'),a=document.getElementById('custAddr');
  if(c)c.addEventListener('change',refreshShip);
  if(a)a.addEventListener('blur',refreshShip);
});
function updateSummary(){
  var qty=parseInt(document.getElementById('qtyInput').value)||1;
  var sub=gOrder.price*qty;var disc=payMode==='BANK'?Math.round(sub*DISCOUNT_PCT/100):0;var after=sub-disc;var ship=after>=FREE_SHIP?0:(liveShip!=null?liveShip:FLAT_SHIP);var total=after+ship;
  document.getElementById('sumPrice').textContent=fmtVnd(gOrder.price);
  document.getElementById('sumQty').textContent=qty;
  document.getElementById('sumDiscount').textContent='-'+fmtVnd(disc);
  document.getElementById('discountRow').style.display=disc>0?'flex':'none';
  document.getElementById('sumShip').textContent=ship===0?'Miễn phí':fmtVnd(ship);
  document.getElementById('sumTotal').textContent=fmtVnd(total);
  gOrder.qty=qty;gOrder.sub=sub;gOrder.disc=disc;gOrder.afterDisc=after;gOrder.ship=ship;gOrder.total=total;
}
function changeQty(d){var i=document.getElementById('qtyInput');i.value=Math.min(99,Math.max(1,parseInt(i.value)+d));updateSummary();}
function changeDetailQty(d){var i=document.getElementById('detailQty');i.value=Math.min(99,Math.max(1,parseInt(i.value)+d));}
async function handleSubmit(){
  var name=document.getElementById('custName').value.trim();var phone=document.getElementById('custPhone').value.trim();
  var city=document.getElementById('custCity').value;var addr=document.getElementById('custAddr').value.trim();
  if(!name){showToast('⚠️ Vui lòng nhập họ tên');return;}if(!phone){showToast('⚠️ Vui lòng nhập SĐT');return;}
  if(!city){showToast('⚠️ Vui lòng chọn tỉnh/thành');return;}if(!addr){showToast('⚠️ Vui lòng nhập địa chỉ');return;}
  gOrder.custName=name;gOrder.phone=phone;gOrder.city=city;gOrder.addr=addr;gOrder.note=document.getElementById('custNote').value.trim();gOrder.payMode=payMode;
  var btn=document.getElementById('submitBtn');btn.disabled=true;document.getElementById('submitText').textContent='⏳ Đang xử lý...';
  try{
    var res=await fetch('{{ route("place-order") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({product_id:gOrder.productId,product_name:gOrder.name,product_size:gOrder.size,price:gOrder.price,quantity:gOrder.qty,customer_name:gOrder.custName,customer_phone:gOrder.phone,customer_city:gOrder.city,customer_addr:gOrder.addr,note:gOrder.note,payment:gOrder.payMode,coupon_code:'',customer_email:(document.getElementById('custEmail')?document.getElementById('custEmail').value.trim():'')})});
    var d=await res.json();
    if(d.success){gOrder.code=d.code;gOrder.total=d.total;if(gOrder.payMode==='BANK'){goToQR();}else{document.getElementById('successCode').textContent=d.code;showState('success');document.getElementById('modalTitle').textContent='✅ Đặt hàng thành công';document.getElementById('modalSub').textContent='';}}
    else showToast('❌ '+(d.message||'Có lỗi xảy ra'));
  }catch(e){showToast('❌ Lỗi kết nối');}
  btn.disabled=false;document.getElementById('submitText').textContent='Tiếp theo →';
}
function goToQR(){
  var amt=gOrder.total;var note=gOrder.code+' DALI';
  document.getElementById('qrImg').src='https://img.vietqr.io/image/'+CFG_BANK_ID+'-'+CFG_BANK_ACC+'-qr_only.png?amount='+Math.round(amt)+'&addInfo='+encodeURIComponent(note)+'&accountName='+encodeURIComponent(CFG_BANK_NAME);
  document.getElementById('qrAmount').textContent=fmtVnd(amt);document.getElementById('bi-bank').textContent=CFG_BANK_LABEL;document.getElementById('bi-acc').textContent=CFG_BANK_ACC;document.getElementById('bi-name').textContent=CFG_BANK_NAME;document.getElementById('bi-amount').textContent=fmtVnd(amt);document.getElementById('bi-note').textContent=note;
  gOrder.qrAmt=amt;gOrder.qrNote=note;showState('qr');startCountdown();
  document.getElementById('modalTitle').textContent='💳 Thanh toán QR';document.getElementById('modalSub').textContent='Quét mã và chuyển khoản đúng nội dung';
}
function startCountdown(){if(gCountdown)clearInterval(gCountdown);var secs=900;var box=document.getElementById('countdownBox');var disp=document.getElementById('countdownDisplay');function tick(){var m=Math.floor(secs/60);var s=secs%60;disp.textContent=(m<10?'0':'')+m+':'+(s<10?'0':'')+s;if(secs<=120)box.classList.add('urgent');else box.classList.remove('urgent');if(secs<=0){clearInterval(gCountdown);showToast('⚠️ Hết thời gian. Vui lòng đặt lại.');}secs--;}tick();gCountdown=setInterval(tick,1000);}
async function customerConfirmPaid(){
  var btn=document.getElementById('paidBtn');btn.disabled=true;btn.textContent='⏳ Đang gửi...';
  if(gCountdown){clearInterval(gCountdown);gCountdown=null;}
  try{await fetch('{{ route("place-order") }}/confirm',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({code:gOrder.code})});}catch(e){}
  document.getElementById('waitOrderCode').textContent=gOrder.code;showState('waiting');document.getElementById('modalTitle').textContent='⏳ Chờ xác nhận';document.getElementById('modalSub').textContent='Shop đang kiểm tra thanh toán';
}
function copyText(id){navigator.clipboard.writeText(document.getElementById(id).textContent).then(()=>showToast('✅ Đã sao chép!'));}
function copyAmountRaw(){navigator.clipboard.writeText(Math.round(gOrder.qrAmt).toString()).then(()=>showToast('✅ Đã sao chép'));}
function showToast(msg){var t=document.getElementById('toast');t.textContent=msg;t.classList.add('show');setTimeout(()=>t.classList.remove('show'),3000);}
function toggleMenu(){document.getElementById('mobileNav').classList.toggle('open');}
var lastST=0,navVis=true;window.addEventListener('scroll',function(){var nav=document.getElementById('mainNav');if(!nav)return;var st=window.scrollY;if(st>200){if(st>lastST&&navVis){nav.classList.add('nav-hidden');navVis=false;}else if(st<lastST&&!navVis){nav.classList.remove('nav-hidden');navVis=true;}}lastST=st<=0?0:st;});
document.getElementById('qtyInput').addEventListener('input',updateSummary);
</script>

@include('partials.float-widget')
@include('partials.bottom-nav')
</body>
</html>