import{_ as t}from"./uni-nav-bar.HncgR8y-.js";import{V as a,T as i,aC as e,x as l,y as n,D as o,H as r,Y as s,A as u,F as c,$ as d,C as m,Z as f,aM as p,I as x,aN as g,aO as k,z as _,aP as h,G as b,aQ as v,aR as C,aS as I,O as $}from"./index-DXZh4TrB.js";import{a as j,r as y}from"./uni-app.es.BpJJwZA9.js";import{_ as w,a as F}from"./uni-list.CgD_ZpdX.js";import{_ as A}from"./u-button.MNc3G8B8.js";import{g as L}from"./wallet.DeHAqHID.js";import{a as N}from"./index.qP49loVj.js";import{_ as O}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.CnM6HZsg.js";import"./uni-status-bar.BUxDt1qs.js";import"./uni-badge.-j3Yi6As.js";import"./u-loading-icon.D8mSLZ2D.js";import"./u-icon.CtR5oXjZ.js";const P=O({__name:"user-center-profile",setup(O){const P=a({}),E=a({});let H=a("");const Q=a();j((()=>async function(){const t=await L();if(t.data&&t.data.info){const a=t.data.info;E.value=a,B(a)}}())),N().then((t=>{P.value=t.data.module}));const S=i(),z=e((()=>S.userinfo)),B=t=>{t.id_card&&0===t.id_auth?H.value="实名中":t.id_card||0!==t.id_auth?1===t.id_auth?H.value=t.id_card||"已认证":2===t.id_auth&&(H.value="重新认证"):H.value="去认证"};return async function(){const{data:t}=await d();Q.value=t.module}(),(a,i)=>{const e=y(l("uni-nav-bar"),t),d=y(l("uni-list-item"),w),j=$,L=m,N=y(l("uni-list"),F),O=y(l("up-button"),A);return n(),o(c,null,[r(e,{title:a.$t("page.title.personalInformation"),border:!1,"status-bar":"","left-icon":"left","background-color":"#F8F8F8",fixed:"",onClickLeft:s(f)},null,8,["title","onClickLeft"]),r(L,{class:"body"},{default:u((()=>[r(N,{border:""},{default:u((()=>[r(L,{class:"phone-number-item",onClick:s(p)},{default:u((()=>[r(d,{title:a.$t("personalInformation.text.phoneNumber"),"right-text":z.value.mobile&&`${z.value.mobile}          `,clickable:"",link:""},null,8,["title","right-text"]),r(j,{class:"alter-text"},{default:u((()=>[x("修改")])),_:1})])),_:1},8,["onClick"]),r(d,{title:a.$t("personalInformation.text.verified"),"right-text":s(H),onClick:i[0]||(i[0]=t=>function(t){if(0===t.id_auth&&t.id_card||1===t.id_auth)return!1;I(t)}({id_auth:E.value.id_auth,id_card:E.value.id_card})),clickable:"",link:"",disabled:0===E.value.id_auth&&E.value.id_card||1===E.value.id_auth},null,8,["title","right-text","disabled"]),r(d,{title:a.$t("personalInformation.text.loginPassword"),"right-text":a.$t("personalInformation.text.revise"),clickable:"",link:"",onClick:s(g)},null,8,["title","right-text","onClick"]),r(d,{title:a.$t("personalInformation.text.paymentPassword"),clickable:"",link:"","right-text":a.$t("personalInformation.text.modifySet"),onClick:s(k)},null,8,["title","right-text","onClick"]),Q.value&&1===Q.value.email_register?(n(),_(d,{key:0,title:a.$t("personalInformation.text.modifyEmail"),clickable:"",link:"","right-text":z.value.email||a.$t("personalInformation.text.binding"),onClick:s(h)},null,8,["title","right-text","onClick"])):b("",!0),r(d,{title:a.$t("personalInformation.text.setNickname"),clickable:"",link:"","right-text":z.value.nickname||a.$t("personalInformation.text.settings"),onClick:s(v)},null,8,["title","right-text","onClick"]),1===P.value.google_auth?(n(),_(d,{key:1,title:a.$t("personalInformation.text.googleAuthenticator"),clickable:"",link:"","right-text":z.value.google_auth?a.$t("personalInformation.text.bound"):a.$t("personalInformation.text.binding"),onClick:s(C)},null,8,["title","right-text","onClick"])):b("",!0)])),_:1}),r(L,{class:"logout",onClick:i[1]||(i[1]=t=>s(S).handleLogout(a.$t))},{default:u((()=>[r(O,{text:a.$t("personalInformation.text.signOut"),color:"rgb(255, 69, 0)"},null,8,["text"])])),_:1})])),_:1})],64)}}},[["__scopeId","data-v-82046b9f"]]);export{P as default};
