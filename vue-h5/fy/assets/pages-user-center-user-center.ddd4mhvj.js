import{T as a,aD as e,V as t,x as s,y as l,z as n,A as r,o as i,C as u,H as o,Y as c,I as d,J as f,aG as m,aH as p,D as _,E as g,F as h,aa as v,B as k,G as x,aI as y,O as b,t as w,r as j}from"./index-CRttwwij.js";import{_ as C}from"./u-image.DAa6LJ_P.js";import{c as F,d as T,r as $}from"./uni-app.es.otF8raCd.js";import{_ as B}from"./uni-nav-bar.QMl_o85J.js";import{_ as G}from"./uni-badge.BLqW0Urq.js";import{_ as I}from"./u-button.DTOkec6s.js";import{_ as z}from"./u-icon.CVzxT_4w.js";import{a as D,g as H}from"./wallet.CgTYJ6_p.js";import{a as O}from"./index.CgRNu-Dw.js";import{_ as V}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./u-transition.B9t9JGP4.js";import"./uni-icons.Bz4Sh8gD.js";import"./uni-status-bar.B9okywpV.js";import"./u-loading-icon.Cvek95z1.js";const A=V({__name:"user-center",setup(V){const A=a(),E=e((()=>A.userinfo));let J=t("transparent"),L=t(0),P=t([]),Q=t({}),U=t([]),X=t([]);const Y=t(),q=a=>{1===a.url_type?1===a.url_level?w({url:a.url}):2===a.url_level&&j({url:a.url}):2===a.url_type&&setTimeout((()=>window.open(a.url,"_blank")))};return O().then((a=>{Y.value=a.data.module})),F((()=>{(async()=>{const{data:a,error:e}=await i();0===e&&(U.value=a.user,X.value=a.user_list)})(),(async()=>{const{data:a,error:e}=await D();0===e&&(P.value=a)})(),(async()=>{const{data:a,error:e}=await H();0===e&&(Q.value=a.money,L.value=a.info.msg_num)})()})),T((a=>{a.scrollTop>34?J.value="#ff6b5a":J.value="transparent"})),(a,e)=>{const t=u,i=$(s("up-image"),C),w=$(s("uni-nav-bar"),B),j=b,F=$(s("uni-badge"),G),T=$(s("up-button"),I),D=$(s("up-icon"),z);return l(),n(t,null,{default:r((()=>[o(t,{class:"page-bg"}),o(i,{class:"page-top-bg",src:"/static/user-center/userCenterBackGround.png",width:"100vw",height:"512rpx"}),o(t,{class:"custom-nab-bar"},{default:r((()=>[o(w,{title:"个人中心","status-bar":"",border:!1,"background-color":c(J),color:"#fff",fixed:""},null,8,["background-color"])])),_:1}),o(t,{class:"main-content"},{default:r((()=>[o(t,{class:"user-info"},{default:r((()=>[o(i,{src:"/static/images/default-avatar.png",width:"120rpx",height:"120rpx",shape:"circle"}),o(t,{class:"info-right"},{default:r((()=>[o(t,{class:"info-right-top"},{default:r((()=>[o(j,null,{default:r((()=>[d(f(E.value.nickname||E.value.mobile||E.value.email),1)])),_:1}),o(t,{class:"message-content",onClick:c(m)},{default:r((()=>[o(i,{src:"/static/user-center/message.png",width:"44rpx",height:"35rpx"}),o(F,{class:"message-badge",text:c(L)},null,8,["text"])])),_:1},8,["onClick"])])),_:1}),o(t,{class:"edit-data",onClick:c(p)},{default:r((()=>[o(T,{color:"rgba(0, 0, 0, 0.1)",hairline:"",shape:"circle",text:"编辑资料"})])),_:1},8,["onClick"])])),_:1})])),_:1}),o(t,{class:"account-assets"},{default:r((()=>[o(t,{class:"account-assets-top"},{default:r((()=>[o(i,{src:"/static/user-center/assets.png",width:"30rpx",height:"30rpx"}),o(j,{class:"account-assets-title"},{default:r((()=>[d("账号资产")])),_:1}),o(j,{class:"account-assets-amount"},{default:r((()=>[d(f(c(Q).total),1)])),_:1})])),_:1}),o(t,{class:"assets-detail"},{default:r((()=>[(l(!0),_(h,null,g(c(P),(a=>(l(),n(t,{key:a.name,style:v(`background: url(${a.img_url})`),class:"assets-detail-item"},{default:r((()=>[o(j,null,{default:r((()=>[d(f(a.name),1)])),_:2},1024),o(j,null,{default:r((()=>[d(f(c(Q)[a.money]),1)])),_:2},1024)])),_:2},1032,["style"])))),128))])),_:1})])),_:1}),c(U).length?(l(),n(t,{key:0,style:v(`grid-template-columns: repeat(${c(U).length>=4?4:c(U).length}, 1fr)`),class:k(["main-option",{"main-option-grid4":c(U).length>=4}])},{default:r((()=>[(l(!0),_(h,null,g(c(U),(a=>(l(),n(t,{key:a.name,class:"main-option-item",onClick:e=>q(a)},{default:r((()=>[o(i,{src:a.img_url,width:"76rpx",height:"76rpx"},null,8,["src"]),o(j,null,{default:r((()=>[d(f(a.name),1)])),_:2},1024)])),_:2},1032,["onClick"])))),128))])),_:1},8,["style","class"])):x("",!0),Y.value&&1===Y.value.my_rights?(l(),n(t,{key:1,class:"invite-friends",onClick:c(y)},{default:r((()=>[o(t,{class:"invite-friends-title"},{default:r((()=>[o(j,null,{default:r((()=>[d("全新合伙模式")])),_:1}),o(j,null,{default:r((()=>[d("查看我的权益")])),_:1})])),_:1}),o(t,{class:"invite-friends-main"},{default:r((()=>[o(t,{class:"invite-friends-main-left"},{default:r((()=>[o(i,{src:"/static/user-center/inviter.png",width:"85rpx",height:"85rpx"}),o(t,{class:"invite-friends-main-left-inner"},{default:r((()=>[o(t,{class:"invite-friends-main-left-inner-title1"},{default:r((()=>[o(j,null,{default:r((()=>[d("邀请好友领高额返佣")])),_:1}),o(D,{name:"arrow-right",color:"#676767",size:"14"})])),_:1}),o(j,null,{default:r((()=>[d("高额佣金送不停")])),_:1})])),_:1})])),_:1}),o(T,{color:"linear-gradient(-45deg, #FF934C, #FF5B63)",shape:"circle",text:"立即邀请"})])),_:1})])),_:1},8,["onClick"])):x("",!0),c(X).length?(l(),n(t,{key:2,class:"secondary-option"},{default:r((()=>[(l(!0),_(h,null,g(c(X),(a=>(l(),n(t,{class:"secondary-option-item",key:a.name,onClick:e=>q(a)},{default:r((()=>[o(i,{src:a.img_url,width:"50rpx",height:"50rpx"},null,8,["src"]),o(j,null,{default:r((()=>[d(f(a.name),1)])),_:2},1024)])),_:2},1032,["onClick"])))),128))])),_:1})):x("",!0),o(t,{class:"log-out"},{default:r((()=>[c(A).token?(l(),n(T,{key:0,color:"#FF4500",text:"退出登录",onClick:e[0]||(e[0]=e=>c(A).handleLogout(a.$t))})):x("",!0)])),_:1})])),_:1})])),_:1})}}},[["__scopeId","data-v-3ad8d22a"]]);export{A as default};
