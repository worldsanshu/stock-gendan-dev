import{_ as t}from"./uni-nav-bar.C3uGE-x6.js";import{V as a,T as i,aD as e,x as l,y as n,D as o,H as r,Y as s,A as u,F as c,C as d,Z as m,bz as f,I as p,bA as x,bB as b,bC as _,bD as g,z as k,bE as h,G as v,bF as C,O as $}from"./index-DKUjMFTs.js";import{c as I,r as j}from"./uni-app.es.UMZ84mjS.js";import{_ as y,a as F}from"./uni-list.hgcr6lD4.js";import{_ as w}from"./u-button.BgUQ4zBz.js";import{g as A}from"./wallet.C3weFbow.js";import{a as D}from"./index.DDVMl6IJ.js";import{_ as L}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.Bi1FBUZy.js";import"./uni-status-bar.Cc1GEu0z.js";import"./uni-badge.QOIR7Lh_.js";import"./u-loading-icon.CEkpOwBA.js";import"./u-icon.DqEvg06s.js";const N=L({__name:"user-center-profile",setup(L){const N=a({}),O=a({});let z=a("");I((()=>async function(){const t=await A();if(t.data&&t.data.info){const a=t.data.info;O.value=a,S(a)}}())),D().then((t=>{N.value=t.data.module}));const E=i(),P=e((()=>E.userinfo)),S=t=>{t.id_card&&0===t.id_auth?z.value="实名中":t.id_card||0!==t.id_auth?1===t.id_auth?z.value=t.id_card||"已认证":2===t.id_auth&&(z.value="重新认证"):z.value="去认证"};return(a,i)=>{const e=j(l("uni-nav-bar"),t),I=j(l("uni-list-item"),y),A=$,D=d,L=j(l("uni-list"),F),S=j(l("up-button"),w);return n(),o(c,null,[r(e,{title:a.$t("page.title.personalInformation"),"status-bar":"","left-icon":"left","background-color":"#F8F8F8",fixed:"",border:!1,onClickLeft:s(m)},null,8,["title","onClickLeft"]),r(D,{class:"body"},{default:u((()=>[r(L,{border:""},{default:u((()=>[r(D,{class:"phone-number-item",onClick:s(f)},{default:u((()=>[r(I,{title:a.$t("personalInformation.text.phoneNumber"),"right-text":P.value.mobile&&`${P.value.mobile}          `,clickable:"",link:""},null,8,["title","right-text"]),r(A,{class:"alter-text"},{default:u((()=>[p("修改")])),_:1})])),_:1},8,["onClick"]),r(I,{title:a.$t("personalInformation.text.verified"),"right-text":s(z),onClick:i[0]||(i[0]=t=>function(t){if(0===t.id_auth&&t.id_card||1===t.id_auth)return!1;C(t)}({id_auth:O.value.id_auth,id_card:O.value.id_card})),clickable:"",link:"",disabled:0===O.value.id_auth&&O.value.id_card||1===O.value.id_auth},null,8,["title","right-text","disabled"]),r(I,{title:a.$t("personalInformation.text.loginPassword"),clickable:"",link:"","right-text":a.$t("personalInformation.text.revise"),onClick:s(x)},null,8,["title","right-text","onClick"]),r(I,{title:a.$t("personalInformation.text.paymentPassword"),clickable:"",link:"","right-text":a.$t("personalInformation.text.modifySet"),onClick:s(b)},null,8,["title","right-text","onClick"]),r(I,{title:a.$t("personalInformation.text.modifyEmail"),clickable:"",link:"","right-text":P.value.email||a.$t("personalInformation.text.binding"),onClick:s(_)},null,8,["title","right-text","onClick"]),r(I,{title:a.$t("personalInformation.text.setNickname"),clickable:"",link:"","right-text":P.value.nickname||a.$t("personalInformation.text.settings"),onClick:s(g)},null,8,["title","right-text","onClick"]),1==N.value.google_auth?(n(),k(I,{key:0,title:a.$t("personalInformation.text.googleAuthenticator"),clickable:"",link:"","right-text":P.value.google_auth?a.$t("personalInformation.text.bound"):a.$t("personalInformation.text.binding"),onClick:s(h)},null,8,["title","right-text","onClick"])):v("",!0)])),_:1}),r(D,{class:"logout",onClick:i[1]||(i[1]=t=>s(E).handleLogout(a.$t))},{default:u((()=>[r(S,{text:a.$t("personalInformation.text.signOut"),color:"rgb(255, 69, 0)"},null,8,["text"])])),_:1})])),_:1})],64)}}},[["__scopeId","data-v-1121ce85"]]);export{N as default};
