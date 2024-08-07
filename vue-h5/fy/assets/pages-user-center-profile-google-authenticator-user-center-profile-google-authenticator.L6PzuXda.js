import{R as e,V as t,T as o,U as a,x as s,D as r,H as l,Y as i,z as n,F as c,aU as u,q as p,B as m,C as d,Z as g,I as f,J as _,aV as h,y as b,G as w,aW as x}from"./index-hU8OKPmH.js";import{_ as j}from"./uni-nav-bar.U3Cmk77Z.js";import{r as v}from"./uni-app.es.DADla7ur.js";import{_ as y}from"./u-image.hr0f9LKz.js";import{_ as k}from"./uni-icons.DgCYXD5d.js";import{_ as V}from"./uni-section.CzVIXMN3.js";import{_ as A}from"./u-input.CvbCMzA9.js";import{_ as C,a as q}from"./u-form.Cwk_KWaU.js";import{_ as $}from"./u-button.CBnbv77V.js";import{_ as B}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-status-bar.BC4SStPU.js";import"./u-icon.BX8bEo6J.js";import"./u-transition.hFcAyfbx.js";import"./u-line.ILQrRGaQ.js";import"./u-loading-icon.DGLmxP69.js";const U=B(Object.assign({name:"user-center-profile-google-authenticator"},{__name:"user-center-profile-google-authenticator",setup(B){const{t:U}=e(),F=t({}),I=o(),D=t(),H=a({code:"",secret:"",password:""}),J={code:{type:"string",required:!0,message:U("googleAuthenticator.text.tip3"),trigger:["blur"]},password:{type:"string",required:!0,message:U("googleAuthenticator.text.tip4"),trigger:["blur"]}};async function L(){try{await D.value.validate();const{error:e,message:t}=await x(H);await p({title:t,icon:e?"none":"success"}),e||(await I.refreshUserinfo(),g())}catch(e){e.length&&await p({title:e[0].message,icon:"none"})}}return async function(){const{error:e,data:t,message:o}=await u();e?await p({title:o,icon:"none"}):(F.value=t,H.secret=t.secret)}(),(e,t)=>{const o=v(s("uni-nav-bar"),j),a=v(s("up-image"),y),u=v(s("uni-icons"),k),p=m,x=v(s("uni-section"),V),B=v(s("up-input"),A),U=v(s("up-form-item"),C),I=v(s("up-form"),q),P=v(s("up-button"),$);return d(),r(c,null,[l(o,{title:e.$t("page.title.googleAuthenticator"),"status-bar":"","left-icon":"left","background-color":"#F8F8F8",fixed:"",border:!1,onClickLeft:i(g)},null,8,["title","onClickLeft"]),l(p,{class:"body p-30"},{default:n((()=>[l(x,{class:"column-align-center",title:e.$t("googleAuthenticator.text.tip1"),type:"line"},{default:n((()=>[l(p,{class:"column-align-center"},{default:n((()=>[l(a,{"show-loading":!0,src:F.value.qrcod_url,width:"260rpx",height:"260rpx"},null,8,["src"]),l(p,{class:"mt-30"},{default:n((()=>[f(_(F.value.secret)+" ",1),l(u,{"custom-prefix":"fc-iconfont",type:"icon-copy",onClick:t[0]||(t[0]=e=>i(h)(F.value.secret))})])),_:1})])),_:1})])),_:1},8,["title"]),l(x,{class:"column-align-center mt-30",title:e.$t("googleAuthenticator.text.tip2"),type:"line"},null,8,["title"]),l(p,{class:"form"},{default:n((()=>[l(I,{class:"plr-20","label-width":"120",labelPosition:"top",model:H,rules:J,ref_key:"formRef",ref:D},{default:n((()=>[l(U,{required:"","left-icon":"lock",label:e.$t("common.text.verificationCode"),prop:"code",borderBottom:""},{default:n((()=>[l(B,{modelValue:H.code,"onUpdate:modelValue":t[1]||(t[1]=e=>H.code=e),placeholder:J.code.message},null,8,["modelValue","placeholder"])])),_:1},8,["label"]),F.value.secret?(d(),b(U,{key:0,required:"","left-icon":"lock",label:e.$t("googleAuthenticator.text.loginPassword"),prop:"password",borderBottom:""},{default:n((()=>[l(B,{modelValue:H.password,"onUpdate:modelValue":t[2]||(t[2]=e=>H.password=e),placeholder:J.password.message},null,8,["modelValue","placeholder"])])),_:1},8,["label"])):w("",!0)])),_:1},8,["model"]),l(P,{class:"mt-30",color:"rgb(255, 69, 0)",text:e.$t("googleAuthenticator.text.confirmBinding"),onClick:L},null,8,["text"])])),_:1})])),_:1})],64)}}}),[["__scopeId","data-v-81dc8748"]]);export{U as default};
