import{_ as t}from"./uni-nav-bar.DGUi_IvM.js";import{V as a,T as i,aC as e,x as l,D as n,H as o,Y as r,z as s,F as u,$ as c,B as d,C as m,Z as f,aM as p,I as x,aN as g,aO as k,y as _,aP as h,G as b,aQ as v,aR as $,aS as C,O as I}from"./index-DugkB_-4.js";import{a as j,r as y}from"./uni-app.es.CX-CC-lz.js";import{_ as w,a as F}from"./uni-list.Di66fBPU.js";import{_ as L}from"./u-button.D_9ELcMi.js";import{g as N}from"./wallet.BMBhKyJb.js";import{a as O}from"./index.CCikdCXX.js";import{_ as P}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.D9BxN9k6.js";import"./uni-status-bar.D_XiF9iL.js";import"./uni-badge.veoJrIAT.js";import"./u-loading-icon.CCS9DSWM.js";import"./u-icon.Ca1c56qc.js";const A=P({__name:"user-center-profile",setup(P){const A=a({}),B=a({});let E=a("");const H=a();j((()=>async function(){const t=await N();if(t.data&&t.data.info){const a=t.data.info;B.value=a,V(a)}}())),O().then((t=>{A.value=t.data.module}));const Q=i(),S=e((()=>Q.userinfo)),V=t=>{t.id_card&&0===t.id_auth?E.value="实名中":t.id_card||0!==t.id_auth?1===t.id_auth?E.value=t.id_card||"已认证":2===t.id_auth&&(E.value="重新认证"):E.value="去认证"};return async function(){const{data:t}=await c();H.value=t.module}(),(a,i)=>{const e=y(l("uni-nav-bar"),t),c=y(l("uni-list-item"),w),j=I,N=d,O=y(l("uni-list"),F),P=y(l("up-button"),L);return m(),n(u,null,[o(e,{title:a.$t("page.title.personalInformation"),border:!1,"status-bar":"","left-icon":"left","background-color":"#F8F8F8",fixed:"",onClickLeft:r(f)},null,8,["title","onClickLeft"]),o(N,{class:"body"},{default:s((()=>[o(O,{border:""},{default:s((()=>[o(N,{class:"phone-number-item",onClick:r(p)},{default:s((()=>[o(c,{title:a.$t("personalInformation.text.phoneNumber"),"right-text":S.value.mobile&&`${S.value.mobile}          `,clickable:"",link:""},null,8,["title","right-text"]),o(j,{class:"alter-text"},{default:s((()=>[x("修改")])),_:1})])),_:1},8,["onClick"]),o(c,{title:a.$t("personalInformation.text.verified"),"right-text":r(E),onClick:i[0]||(i[0]=t=>function(t){if(0===t.id_auth&&t.id_card||1===t.id_auth)return!1;C(t)}({id_auth:B.value.id_auth,id_card:B.value.id_card})),clickable:"",link:"",disabled:0===B.value.id_auth&&B.value.id_card||1===B.value.id_auth},null,8,["title","right-text","disabled"]),o(c,{title:a.$t("personalInformation.text.loginPassword"),"right-text":a.$t("personalInformation.text.revise"),clickable:"",link:"",onClick:r(g)},null,8,["title","right-text","onClick"]),o(c,{title:a.$t("personalInformation.text.paymentPassword"),clickable:"",link:"","right-text":a.$t("personalInformation.text.modifySet"),onClick:r(k)},null,8,["title","right-text","onClick"]),H.value&&1===H.value.email_register?(m(),_(c,{key:0,title:a.$t("personalInformation.text.modifyEmail"),clickable:"",link:"","right-text":S.value.email||a.$t("personalInformation.text.binding"),onClick:r(h)},null,8,["title","right-text","onClick"])):b("",!0),o(c,{title:a.$t("personalInformation.text.setNickname"),clickable:"",link:"","right-text":S.value.nickname||a.$t("personalInformation.text.settings"),onClick:r(v)},null,8,["title","right-text","onClick"]),1===A.value.google_auth?(m(),_(c,{key:1,title:a.$t("personalInformation.text.googleAuthenticator"),clickable:"",link:"","right-text":S.value.google_auth?a.$t("personalInformation.text.bound"):a.$t("personalInformation.text.binding"),onClick:r($)},null,8,["title","right-text","onClick"])):b("",!0),o(c,{title:"版本号","right-text":`V${r("1.0.5")}`},null,8,["right-text"])])),_:1}),o(N,{class:"logout",onClick:i[1]||(i[1]=t=>r(Q).handleLogout(a.$t))},{default:s((()=>[o(P,{text:a.$t("personalInformation.text.signOut"),color:"rgb(255, 69, 0)"},null,8,["text"])])),_:1})])),_:1})],64)}}},[["__scopeId","data-v-821de221"]]);export{A as default};
