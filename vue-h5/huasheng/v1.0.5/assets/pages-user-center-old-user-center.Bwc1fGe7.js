import{T as t,aC as a,V as s,o as e,aD as l,aE as n,x as c,y as i,z as u,B as o,C as r,H as m,Y as _,aF as f,I as d,J as p,aG as g,D as v,E as x,F as y,aH as b,G as k,N as h,t as j,r as C}from"./index-hU8OKPmH.js";import{_ as w}from"./uni-icons.DgCYXD5d.js";import{a as $,r as L}from"./uni-app.es.DADla7ur.js";import{_ as U}from"./uni-badge.CJQLJhSm.js";import{_ as A}from"./u-avatar.CL9_xXf2.js";import{_ as z,a as F}from"./uni-list.DsLuk3Ly.js";import{_ as I}from"./u-button.CBnbv77V.js";import{a as R,i as B}from"./index.DUzjlh-0.js";import{U as D}from"./uni-status-bar.BC4SStPU.js";import{g as E}from"./wallet.VcNaQDUL.js";import{_ as G}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./u-icon.BX8bEo6J.js";import"./u-text.BOVY5aNF.js";import"./u-link.BdNvV42H.js";import"./u-loading-icon.DGLmxP69.js";const H=""+new URL("balance-C26h0NF1.png",import.meta.url).href,J=""+new URL("margin-CJKe_HsQ.png",import.meta.url).href,N=""+new URL("active-funds-c2xUr-WB.png",import.meta.url).href,Y=""+new URL("frozen-assets-DhAxbkZI.png",import.meta.url).href,K=""+new URL("invite-friend-FAY7KeGY.png",import.meta.url).href,O=G(Object.assign({name:"user-center"},{__name:"user-center",setup(G){const O=t(),Q=a((()=>O.userinfo)),P=s({}),T=s({}),V=s({}),W=s({}),X=s(0);let Z=s([]),q=s([]);R().then((t=>{T.value=t.data.module})),B({}).then((t=>{V.value=t.data})),$((()=>{M(),async function(){const t=await E();t.data&&t.data.money&&(P.value=t.data.money,X.value=t.data.info.msg_num,W.value=t.data.info)}()}));const M=async()=>{const t=await e();0===t.error&&(Z.value=t.data.user,q.value=t.data.user_list)};const S=t=>{1==t.url_type?1==t.url_level?j({url:t.url}):2==t.url_level&&C({url:t.url}):2==t.url_type&&window.open(t.url,"_blank")},tt=s(l());return console.log("currentLang",tt),n((t=>{tt.value=t.locale})),(t,a)=>{const s=L(c("uni-icons"),w),e=L(c("uni-badge"),U),l=o,n=L(c("up-avatar"),A),j=h,C=L(c("uni-list-item"),z),$=L(c("uni-list"),F),R=L(c("up-button"),I);return r(),i(l,{class:"user-center"},{default:u((()=>[m(D),m(l,{class:"top"},{default:u((()=>[m(l,{class:"top-right-icon"},{default:u((()=>[m(s,{type:"email",size:"30",onClick:_(f)},null,8,["onClick"]),m(e,{class:"top-right-num",text:X.value},null,8,["text"])])),_:1})])),_:1}),m(l,{class:"header"},{default:u((()=>[m(n,{src:"/static/images/default-avatar.png"}),m(l,{class:"font-18 ml-30 mr-20 u-flex-fill ellipsis-1"},{default:u((()=>[d(p(Q.value.nickname||Q.value.mobile||Q.value.email),1)])),_:1}),m(l,{class:"header-btn",onClick:_(g)},{default:u((()=>[d(p(t.$t("my.text.editInformation")),1)])),_:1},8,["onClick"])])),_:1}),m(l,{class:"label"},{default:u((()=>[d(p(t.$t("my.text.myAssets")),1)])),_:1}),m(l,{class:"assets-wrapper"},{default:u((()=>[m(l,{class:"assets"},{default:u((()=>[m(l,{class:"assets-item"},{default:u((()=>[m(l,{class:"assets-item__content"},{default:u((()=>[m(l,{class:"font-12"},{default:u((()=>[d(p(t.$t("my.text.accountAssets")),1)])),_:1}),m(l,{class:"font-14"},{default:u((()=>[d(p(P.value.total),1)])),_:1})])),_:1})])),_:1}),m(l,{class:"assets-item"}),m(l,{class:"assets-item bg"},{default:u((()=>[m(l,{class:"assets-item__content"},{default:u((()=>[m(l,{class:"font-12"},{default:u((()=>[d(p(t.$t("my.text.accountBalance")),1)])),_:1}),m(l,{class:"font-14"},{default:u((()=>[d(p(P.value.account),1)])),_:1})])),_:1}),m(j,{class:"assets-item__image",src:H})])),_:1}),m(l,{class:"assets-item bg"},{default:u((()=>[m(l,{class:"assets-item__content"},{default:u((()=>[m(l,{class:"font-12"},{default:u((()=>[d(p(t.$t("my.text.margin")),1)])),_:1}),m(l,{class:"font-14"},{default:u((()=>[d(p(P.value.bond_account),1)])),_:1})])),_:1}),m(j,{class:"assets-item__image",src:J})])),_:1}),m(l,{class:"assets-item bg"},{default:u((()=>[m(l,{class:"assets-item__content"},{default:u((()=>[m(l,{class:"font-12"},{default:u((()=>[d(p(t.$t("my.text.activityFund")),1)])),_:1}),m(l,{class:"font-14"},{default:u((()=>[d(p(P.value.activity_account),1)])),_:1})])),_:1}),m(j,{class:"assets-item__image",src:N})])),_:1}),m(l,{class:"assets-item bg"},{default:u((()=>[m(l,{class:"assets-item__content"},{default:u((()=>[m(l,{class:"font-12"},{default:u((()=>[d(p(t.$t("my.text.freezingAssets")),1)])),_:1}),m(l,{class:"font-14"},{default:u((()=>[d(p(P.value.freeze),1)])),_:1})])),_:1}),m(j,{class:"assets-item__image",src:Y})])),_:1}),m(l,{class:"assets-item bg"},{default:u((()=>[m(l,{class:"assets-item__content"},{default:u((()=>[m(l,{class:"font-12"},{default:u((()=>[d("持仓资产")])),_:1}),m(l,{class:"font-14"},{default:u((()=>[d(p(P.value.contract_gd),1)])),_:1})])),_:1}),m(j,{class:"assets-item__image",src:N})])),_:1})])),_:1})])),_:1}),m(l,{class:"actions"},{default:u((()=>[(r(!0),v(y,null,x(_(Z),(t=>(r(),i(l,{key:t.name,class:"actions-item",onClick:a=>S(t)},{default:u((()=>[m(j,{class:"actions-item__image",src:t.img_url},null,8,["src"]),m(l,{class:"actions-item__name"},{default:u((()=>[d(p(t.name),1)])),_:2},1024)])),_:2},1032,["onClick"])))),128))])),_:1}),m(l,{class:"card",onClick:_(b)},{default:u((()=>[m(l,{class:"card-header"},{default:u((()=>[m(l,{class:"font-16-inverse"},{default:u((()=>[d(p(t.$t("my.text.tip1")),1)])),_:1}),m(l,{class:"font-12-inverse"},{default:u((()=>[d(p(t.$t("my.text.tip2")),1)])),_:1})])),_:1}),m(l,{class:"card-content"},{default:u((()=>[m(j,{class:"card-content__left",src:K}),m(l,{class:"card-content__middle"},{default:u((()=>[m(l,{class:"font-14"},{default:u((()=>[d(p(t.$t("my.text.tip3"))+">",1)])),_:1}),m(l,{class:"font-12 mt-10"},{default:u((()=>[d(p(t.$t("my.text.tip4")),1)])),_:1})])),_:1}),m(l,{class:"card-content__right"},{default:u((()=>[d(p(t.$t("my.text.inviteNow")),1)])),_:1})])),_:1})])),_:1},8,["onClick"]),_(q).length?(r(),i($,{key:0,class:"mt-30"},{default:u((()=>[(r(!0),v(y,null,x(_(q),(t=>(r(),i(C,{key:t.name,title:t.name,"show-extra-icon":"","extra-icon":{type:t.img_url,customPrefix:"fc-iconfont"},clickable:"",link:"",onClick:a=>S(t)},null,8,["title","extra-icon","onClick"])))),128))])),_:1})):k("",!0),_(O).token?(r(),i(l,{key:1,class:"logout",onClick:a[0]||(a[0]=a=>_(O).handleLogout(t.$t))},{default:u((()=>[m(R,{text:t.$t("my.text.signOut"),color:"rgb(255, 69, 0)"},null,8,["text"])])),_:1})):k("",!0),m(l,{class:"tab-bat-height"})])),_:1})}}}),[["__scopeId","data-v-66982a30"]]);export{O as default};
