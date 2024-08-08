import{R as e,V as t,T as o,U as a,x as s,D as r,H as l,Y as i,z as n,F as c,aW as u,v as p,B as m,C as d,Z as g,I as f,J as _,aX as h,y as b,G as w,aY as x}from"./index-V1zTiwCe.js";import{_ as j}from"./uni-nav-bar.B9quvnCD.js";import{r as v}from"./uni-app.es.CQrvLY-P.js";import{_ as y}from"./u-image.DpaUX6D5.js";import{_ as k}from"./uni-icons.DZWFI7QG.js";import{_ as A}from"./uni-section.B-Hdsl6J.js";import{_ as C}from"./u-input.CiR-oGCa.js";import{_ as V,a as $}from"./u-form.6y5zeQu3.js";import{_ as q}from"./u-button.B0l9ArWO.js";import{_ as B}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-status-bar.BjedvyN3.js";import"./u-icon.EexHLkhm.js";import"./u-transition.BCj5qmVK.js";import"./u-line.BcX0DQci.js";import"./u-loading-icon.COOKVkTA.js";const F=B(Object.assign({name:"user-center-profile-google-authenticator"},{__name:"user-center-profile-google-authenticator",setup(B){const{t:F}=e(),U=t({}),I=o(),Y=t(),D=a({code:"",secret:"",password:""}),H={code:{type:"string",required:!0,message:F("googleAuthenticator.text.tip3"),trigger:["blur"]},password:{type:"string",required:!0,message:F("googleAuthenticator.text.tip4"),trigger:["blur"]}};async function J(){try{await Y.value.validate();const{error:e,message:t}=await x(D);await p({title:t,icon:e?"none":"success"}),e||(await I.refreshUserinfo(),g())}catch(e){e.length&&await p({title:e[0].message,icon:"none"})}}return async function(){const{error:e,data:t,message:o}=await u();e?await p({title:o,icon:"none"}):(U.value=t,D.secret=t.secret)}(),(e,t)=>{const o=v(s("uni-nav-bar"),j),a=v(s("up-image"),y),u=v(s("uni-icons"),k),p=m,x=v(s("uni-section"),A),B=v(s("up-input"),C),F=v(s("up-form-item"),V),I=v(s("up-form"),$),L=v(s("up-button"),q);return d(),r(c,null,[l(o,{title:e.$t("page.title.googleAuthenticator"),"status-bar":"","left-icon":"left","background-color":"#F8F8F8",fixed:"",border:!1,onClickLeft:i(g)},null,8,["title","onClickLeft"]),l(p,{class:"body p-30"},{default:n((()=>[l(x,{class:"column-align-center",title:e.$t("googleAuthenticator.text.tip1"),type:"line"},{default:n((()=>[l(p,{class:"column-align-center"},{default:n((()=>[l(a,{"show-loading":!0,src:U.value.qrcod_url,width:"260rpx",height:"260rpx"},null,8,["src"]),l(p,{class:"mt-30"},{default:n((()=>[f(_(U.value.secret)+" ",1),l(u,{"custom-prefix":"fc-iconfont",type:"icon-copy",onClick:t[0]||(t[0]=e=>i(h)(U.value.secret))})])),_:1})])),_:1})])),_:1},8,["title"]),l(x,{class:"column-align-center mt-30",title:e.$t("googleAuthenticator.text.tip2"),type:"line"},null,8,["title"]),l(p,{class:"form"},{default:n((()=>[l(I,{class:"plr-20","label-width":"120",labelPosition:"top",model:D,rules:H,ref_key:"formRef",ref:Y},{default:n((()=>[l(F,{required:"","left-icon":"lock",label:e.$t("common.text.verificationCode"),prop:"code",borderBottom:""},{default:n((()=>[l(B,{modelValue:D.code,"onUpdate:modelValue":t[1]||(t[1]=e=>D.code=e),placeholder:H.code.message},null,8,["modelValue","placeholder"])])),_:1},8,["label"]),U.value.secret?(d(),b(F,{key:0,required:"","left-icon":"lock",label:e.$t("googleAuthenticator.text.loginPassword"),prop:"password",borderBottom:""},{default:n((()=>[l(B,{modelValue:D.password,"onUpdate:modelValue":t[2]||(t[2]=e=>D.password=e),placeholder:H.password.message},null,8,["modelValue","placeholder"])])),_:1},8,["label"])):w("",!0)])),_:1},8,["model"]),l(L,{class:"mt-30",color:"rgb(255, 69, 0)",text:e.$t("googleAuthenticator.text.confirmBinding"),onClick:J},null,8,["text"])])),_:1})])),_:1})],64)}}}),[["__scopeId","data-v-81dc8748"]]);export{F as default};
