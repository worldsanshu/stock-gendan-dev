import{R as e,V as t,T as o,U as a,x as s,y as r,D as l,H as i,Y as n,A as c,F as u,bH as p,q as m,C as d,Z as g,I as f,J as _,bI as b,z as h,G as w,bJ as x}from"./index-CUCz9quq.js";import{_ as j}from"./uni-nav-bar.B5kmKt-e.js";import{r as y}from"./uni-app.es.B7l1sWMt.js";import{_ as v}from"./u-image.DT2ESGXs.js";import{_ as k}from"./uni-icons.AC3xlVii.js";import{_ as A}from"./uni-section.DsioV4G4.js";import{_ as V}from"./u-input.bBUu6_D_.js";import{_ as q,a as $}from"./u-form.uPLdZJMG.js";import{_ as C}from"./u-button.Cc01_ebD.js";import{_ as U}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-status-bar.BnrCe1qF.js";import"./u-icon.C7YV5ppE.js";import"./u-transition.CBEWQ3M6.js";import"./u-line.DX81QOA9.js";import"./u-loading-icon.Dqx9PYiW.js";const F=U(Object.assign({name:"user-center-profile-google-authenticator"},{__name:"user-center-profile-google-authenticator",setup(U){const{t:F}=e(),B=t({}),I=o(),P=t(),H=a({code:"",secret:"",password:""}),J={code:{type:"string",required:!0,message:F("googleAuthenticator.text.tip3"),trigger:["blur"]},password:{type:"string",required:!0,message:F("googleAuthenticator.text.tip4"),trigger:["blur"]}};async function L(){try{await P.value.validate();const{error:e,message:t}=await x(H);await m({title:t,icon:e?"none":"success"}),e||(await I.refreshUserinfo(),g())}catch(e){e.length&&await m({title:e[0].message,icon:"none"})}}return async function(){const{error:e,data:t,message:o}=await p();e?await m({title:o,icon:"none"}):(B.value=t,H.secret=t.secret)}(),(e,t)=>{const o=y(s("uni-nav-bar"),j),a=y(s("up-image"),v),p=y(s("uni-icons"),k),m=d,x=y(s("uni-section"),A),U=y(s("up-input"),V),F=y(s("up-form-item"),q),I=y(s("up-form"),$),O=y(s("up-button"),C);return r(),l(u,null,[i(o,{title:e.$t("page.title.googleAuthenticator"),"status-bar":"","left-icon":"left","background-color":"#F8F8F8",fixed:"",border:!1,onClickLeft:n(g)},null,8,["title","onClickLeft"]),i(m,{class:"body p-30"},{default:c((()=>[i(x,{class:"column-align-center",title:e.$t("googleAuthenticator.text.tip1"),type:"line"},{default:c((()=>[i(m,{class:"column-align-center"},{default:c((()=>[i(a,{"show-loading":!0,src:B.value.qrcod_url,width:"260rpx",height:"260rpx"},null,8,["src"]),i(m,{class:"mt-30"},{default:c((()=>[f(_(B.value.secret)+" ",1),i(p,{"custom-prefix":"fc-iconfont",type:"icon-copy",onClick:t[0]||(t[0]=e=>n(b)(B.value.secret))})])),_:1})])),_:1})])),_:1},8,["title"]),i(x,{class:"column-align-center mt-30",title:e.$t("googleAuthenticator.text.tip2"),type:"line"},null,8,["title"]),i(m,{class:"form"},{default:c((()=>[i(I,{class:"plr-20","label-width":"120",labelPosition:"top",model:H,rules:J,ref_key:"formRef",ref:P},{default:c((()=>[i(F,{required:"","left-icon":"lock",label:e.$t("common.text.verificationCode"),prop:"code",borderBottom:""},{default:c((()=>[i(U,{modelValue:H.code,"onUpdate:modelValue":t[1]||(t[1]=e=>H.code=e),placeholder:J.code.message},null,8,["modelValue","placeholder"])])),_:1},8,["label"]),B.value.secret?(r(),h(F,{key:0,required:"","left-icon":"lock",label:e.$t("googleAuthenticator.text.loginPassword"),prop:"password",borderBottom:""},{default:c((()=>[i(U,{modelValue:H.password,"onUpdate:modelValue":t[2]||(t[2]=e=>H.password=e),placeholder:J.password.message},null,8,["modelValue","placeholder"])])),_:1},8,["label"])):w("",!0)])),_:1},8,["model"]),i(O,{class:"mt-30",color:"rgb(255, 69, 0)",text:e.$t("googleAuthenticator.text.confirmBinding"),onClick:L},null,8,["text"])])),_:1})])),_:1})],64)}}}),[["__scopeId","data-v-81dc8748"]]);export{F as default};