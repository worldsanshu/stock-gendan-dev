import{T as t,aD as a,V as s,o as e,aE as l,aF as n,x as c,y as i,z as u,A as o,C as r,H as m,Y as _,aG as f,I as d,J as p,aH as g,D as v,E as x,F as y,aI as k,G as b,N as h,t as j,r as $}from"./index-v5N4FCa5.js";import{_ as C}from"./uni-icons.DUeGXWcA.js";import{c as w,r as L}from"./uni-app.es.BBb7LdIV.js";import{_ as U}from"./uni-badge.Br37dhTl.js";import{_ as A}from"./u-avatar.Cq0i0dty.js";import{_ as z,a as F}from"./uni-list.Bgjj49Bk.js";import{_ as I}from"./u-button.BgPjuVHs.js";import{a as R,i as N}from"./index.Dm55e1Sd.js";import{U as D}from"./uni-status-bar.D590Ci0c.js";import{g as G}from"./wallet.BuuYT8z2.js";import{_ as H}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./u-icon.C6i9Eu8q.js";import"./u-text.DT-lw-Ey.js";import"./u-link.CwKFm7T8.js";import"./u-loading-icon.Cck0q0wJ.js";const O=""+new URL("balance-C26h0NF1.png",import.meta.url).href,Y=""+new URL("margin-CJKe_HsQ.png",import.meta.url).href,B=""+new URL("active-funds-c2xUr-WB.png",import.meta.url).href,E=""+new URL("frozen-assets-DhAxbkZI.png",import.meta.url).href,J=""+new URL("invite-friend-FAY7KeGY.png",import.meta.url).href,K=H(Object.assign({name:"user-center"},{__name:"user-center",setup(H){const K=t(),T=a((()=>K.userinfo)),W=s({}),P=s({}),Q=s({}),S=s({}),V=s(0);let Z=s([]),q=s([]);R().then((t=>{P.value=t.data.module})),N({}).then((t=>{Q.value=t.data})),w((()=>{M(),async function(){const t=await G();t.data&&t.data.money&&(W.value=t.data.money,V.value=t.data.info.msg_num,S.value=t.data.info)}()}));const M=async()=>{const t=await e();0===t.error&&(Z.value=t.data.user,q.value=t.data.user_list)};const X=t=>{1==t.url_type?1==t.url_level?j({url:t.url}):2==t.url_level&&$({url:t.url}):2==t.url_type&&window.open(t.url,"_blank")},tt=s(l());return console.log("currentLang",tt),n((t=>{tt.value=t.locale})),(t,a)=>{const s=L(c("uni-icons"),C),e=L(c("uni-badge"),U),l=r,n=L(c("up-avatar"),A),j=h,$=L(c("uni-list-item"),z),w=L(c("uni-list"),F),R=L(c("up-button"),I);return i(),u(l,{class:"user-center"},{default:o((()=>[m(D),m(l,{class:"top"},{default:o((()=>[m(l,{class:"top-right-icon"},{default:o((()=>[m(s,{type:"email",size:"30",onClick:_(f)},null,8,["onClick"]),m(e,{class:"top-right-num",text:V.value},null,8,["text"])])),_:1})])),_:1}),m(l,{class:"header"},{default:o((()=>[m(n,{src:"/static/images/default-avatar.png"}),m(l,{class:"font-18 ml-30 mr-20 u-flex-fill ellipsis-1"},{default:o((()=>[d(p(T.value.nickname||T.value.mobile||T.value.email),1)])),_:1}),m(l,{class:"header-btn",onClick:_(g)},{default:o((()=>[d(p(t.$t("my.text.editInformation")),1)])),_:1},8,["onClick"])])),_:1}),m(l,{class:"label"},{default:o((()=>[d(p(t.$t("my.text.myAssets")),1)])),_:1}),m(l,{class:"assets-wrapper"},{default:o((()=>[m(l,{class:"assets"},{default:o((()=>[m(l,{class:"assets-item"},{default:o((()=>[m(l,{class:"assets-item__content"},{default:o((()=>[m(l,{class:"font-12"},{default:o((()=>[d(p(t.$t("my.text.accountAssets")),1)])),_:1}),m(l,{class:"font-14"},{default:o((()=>[d(p(W.value.total),1)])),_:1})])),_:1})])),_:1}),m(l,{class:"assets-item"}),m(l,{class:"assets-item bg"},{default:o((()=>[m(l,{class:"assets-item__content"},{default:o((()=>[m(l,{class:"font-12"},{default:o((()=>[d(p(t.$t("my.text.accountBalance")),1)])),_:1}),m(l,{class:"font-14"},{default:o((()=>[d(p(W.value.account),1)])),_:1})])),_:1}),m(j,{class:"assets-item__image",src:O})])),_:1}),m(l,{class:"assets-item bg"},{default:o((()=>[m(l,{class:"assets-item__content"},{default:o((()=>[m(l,{class:"font-12"},{default:o((()=>[d(p(t.$t("my.text.margin")),1)])),_:1}),m(l,{class:"font-14"},{default:o((()=>[d(p(W.value.bond_account),1)])),_:1})])),_:1}),m(j,{class:"assets-item__image",src:Y})])),_:1}),m(l,{class:"assets-item bg"},{default:o((()=>[m(l,{class:"assets-item__content"},{default:o((()=>[m(l,{class:"font-12"},{default:o((()=>[d(p(t.$t("my.text.activityFund")),1)])),_:1}),m(l,{class:"font-14"},{default:o((()=>[d(p(W.value.activity_account),1)])),_:1})])),_:1}),m(j,{class:"assets-item__image",src:B})])),_:1}),m(l,{class:"assets-item bg"},{default:o((()=>[m(l,{class:"assets-item__content"},{default:o((()=>[m(l,{class:"font-12"},{default:o((()=>[d(p(t.$t("my.text.freezingAssets")),1)])),_:1}),m(l,{class:"font-14"},{default:o((()=>[d(p(W.value.freeze),1)])),_:1})])),_:1}),m(j,{class:"assets-item__image",src:E})])),_:1}),m(l,{class:"assets-item bg"},{default:o((()=>[m(l,{class:"assets-item__content"},{default:o((()=>[m(l,{class:"font-12"},{default:o((()=>[d("持仓资产")])),_:1}),m(l,{class:"font-14"},{default:o((()=>[d(p(W.value.contract_gd),1)])),_:1})])),_:1}),m(j,{class:"assets-item__image",src:B})])),_:1})])),_:1})])),_:1}),m(l,{class:"actions"},{default:o((()=>[(i(!0),v(y,null,x(_(Z),(t=>(i(),u(l,{key:t.name,class:"actions-item",onClick:a=>X(t)},{default:o((()=>[m(j,{class:"actions-item__image",src:t.img_url},null,8,["src"]),m(l,{class:"actions-item__name"},{default:o((()=>[d(p(t.name),1)])),_:2},1024)])),_:2},1032,["onClick"])))),128))])),_:1}),m(l,{class:"card",onClick:_(k)},{default:o((()=>[m(l,{class:"card-header"},{default:o((()=>[m(l,{class:"font-16-inverse"},{default:o((()=>[d(p(t.$t("my.text.tip1")),1)])),_:1}),m(l,{class:"font-12-inverse"},{default:o((()=>[d(p(t.$t("my.text.tip2")),1)])),_:1})])),_:1}),m(l,{class:"card-content"},{default:o((()=>[m(j,{class:"card-content__left",src:J}),m(l,{class:"card-content__middle"},{default:o((()=>[m(l,{class:"font-14"},{default:o((()=>[d(p(t.$t("my.text.tip3"))+">",1)])),_:1}),m(l,{class:"font-12 mt-10"},{default:o((()=>[d(p(t.$t("my.text.tip4")),1)])),_:1})])),_:1}),m(l,{class:"card-content__right"},{default:o((()=>[d(p(t.$t("my.text.inviteNow")),1)])),_:1})])),_:1})])),_:1},8,["onClick"]),_(q).length?(i(),u(w,{key:0,class:"mt-30"},{default:o((()=>[(i(!0),v(y,null,x(_(q),(t=>(i(),u($,{key:t.name,title:t.name,"show-extra-icon":"","extra-icon":{type:t.img_url,customPrefix:"fc-iconfont"},clickable:"",link:"",onClick:a=>X(t)},null,8,["title","extra-icon","onClick"])))),128))])),_:1})):b("",!0),_(K).token?(i(),u(l,{key:1,class:"logout",onClick:a[0]||(a[0]=a=>_(K).handleLogout(t.$t))},{default:o((()=>[m(R,{text:t.$t("my.text.signOut"),color:"rgb(255, 69, 0)"},null,8,["text"])])),_:1})):b("",!0),m(l,{class:"tab-bat-height"})])),_:1})}}}),[["__scopeId","data-v-66982a30"]]);export{K as default};
