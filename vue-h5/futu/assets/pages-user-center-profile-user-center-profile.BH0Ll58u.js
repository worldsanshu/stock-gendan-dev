import{_ as t}from"./uni-nav-bar.b7TMaKob.js";import{V as a,T as i,aD as e,x as l,y as n,D as o,H as r,Y as s,A as u,F as c,a0 as d,C as m,Z as f,bz as p,I as b,bA as x,bB as g,z as _,bC as k,G as h,bD as v,bE as C,bF as $,O as I}from"./index-CM0aLgVp.js";import{c as j,r as y}from"./uni-app.es.DvFBep3n.js";import{_ as w,a as F}from"./uni-list.Con2J_6C.js";import{_ as A}from"./u-button.CnhJ_Nhk.js";import{g as D}from"./wallet.CXsdS-Qy.js";import{a as L}from"./index.DAslwvEt.js";import{_ as N}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.Ccatdggj.js";import"./uni-status-bar.DKMfYGoJ.js";import"./uni-badge.DQvFrxBp.js";import"./u-loading-icon.D6wg7OO0.js";import"./u-icon.D4qWymo2.js";const O=N({__name:"user-center-profile",setup(N){const O=a({}),z=a({});let E=a("");const P=a();j((()=>async function(){const t=await D();if(t.data&&t.data.info){const a=t.data.info;z.value=a,Z(a)}}())),L().then((t=>{O.value=t.data.module}));const S=i(),T=e((()=>S.userinfo)),Z=t=>{t.id_card&&0===t.id_auth?E.value="实名中":t.id_card||0!==t.id_auth?1===t.id_auth?E.value=t.id_card||"已认证":2===t.id_auth&&(E.value="重新认证"):E.value="去认证"};return async function(){const{data:t}=await d();console.log(t),P.value=t.module}(),(a,i)=>{const e=y(l("uni-nav-bar"),t),d=y(l("uni-list-item"),w),j=I,D=m,L=y(l("uni-list"),F),N=y(l("up-button"),A);return n(),o(c,null,[r(e,{title:a.$t("page.title.personalInformation"),"status-bar":"","left-icon":"left","background-color":"#F8F8F8",fixed:"",border:!1,onClickLeft:s(f)},null,8,["title","onClickLeft"]),r(D,{class:"body"},{default:u((()=>[r(L,{border:""},{default:u((()=>[r(D,{class:"phone-number-item",onClick:s(p)},{default:u((()=>[r(d,{title:a.$t("personalInformation.text.phoneNumber"),"right-text":T.value.mobile&&`${T.value.mobile}          `,clickable:"",link:""},null,8,["title","right-text"]),r(j,{class:"alter-text"},{default:u((()=>[b("修改")])),_:1})])),_:1},8,["onClick"]),r(d,{title:a.$t("personalInformation.text.verified"),"right-text":s(E),onClick:i[0]||(i[0]=t=>function(t){if(0===t.id_auth&&t.id_card||1===t.id_auth)return!1;$(t)}({id_auth:z.value.id_auth,id_card:z.value.id_card})),clickable:"",link:"",disabled:0===z.value.id_auth&&z.value.id_card||1===z.value.id_auth},null,8,["title","right-text","disabled"]),r(d,{title:a.$t("personalInformation.text.loginPassword"),clickable:"",link:"","right-text":a.$t("personalInformation.text.revise"),onClick:s(x)},null,8,["title","right-text","onClick"]),r(d,{title:a.$t("personalInformation.text.paymentPassword"),clickable:"",link:"","right-text":a.$t("personalInformation.text.modifySet"),onClick:s(g)},null,8,["title","right-text","onClick"]),P.value&&1===P.value.email_register?(n(),_(d,{key:0,title:a.$t("personalInformation.text.modifyEmail"),clickable:"",link:"","right-text":T.value.email||a.$t("personalInformation.text.binding"),onClick:s(k)},null,8,["title","right-text","onClick"])):h("",!0),r(d,{title:a.$t("personalInformation.text.setNickname"),clickable:"",link:"","right-text":T.value.nickname||a.$t("personalInformation.text.settings"),onClick:s(v)},null,8,["title","right-text","onClick"]),1==O.value.google_auth?(n(),_(d,{key:1,title:a.$t("personalInformation.text.googleAuthenticator"),clickable:"",link:"","right-text":T.value.google_auth?a.$t("personalInformation.text.bound"):a.$t("personalInformation.text.binding"),onClick:s(C)},null,8,["title","right-text","onClick"])):h("",!0)])),_:1}),r(D,{class:"logout",onClick:i[1]||(i[1]=t=>s(S).handleLogout(a.$t))},{default:u((()=>[r(N,{text:a.$t("personalInformation.text.signOut"),color:"rgb(255, 69, 0)"},null,8,["text"])])),_:1})])),_:1})],64)}}},[["__scopeId","data-v-0b9b6687"]]);export{O as default};
