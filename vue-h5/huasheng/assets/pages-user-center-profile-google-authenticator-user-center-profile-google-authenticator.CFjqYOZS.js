import{R as e,V as t,T as o,U as a,x as s,y as r,D as l,H as i,Y as n,A as c,F as u,bI as p,q as m,C as d,Z as g,I as f,J as _,bJ as b,z as h,G as w,bK as x}from"./index-DLU8qqff.js";import{_ as j}from"./uni-nav-bar.CoRAz90g.js";import{r as y}from"./uni-app.es.D9aFg0qL.js";import{_ as v}from"./u-image.mYXttu3I.js";import{_ as k}from"./uni-icons.CHpq13ux.js";import{_ as A}from"./uni-section.DduGaPvx.js";import{_ as V}from"./u-input.BSvGCxOR.js";import{_ as q,a as $}from"./u-form.BRj5kSiB.js";import{_ as C}from"./u-button.B345c9Fe.js";import{_ as U}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-status-bar.BHywJPZs.js";import"./u-icon.BAs1cCP0.js";import"./u-transition.Bp5L5bW5.js";import"./u-line.B-8l58Gh.js";import"./u-loading-icon.-zKdzUZA.js";const F=U(Object.assign({name:"user-center-profile-google-authenticator"},{__name:"user-center-profile-google-authenticator",setup(U){const{t:F}=e(),B=t({}),I=o(),P=t(),J=a({code:"",secret:"",password:""}),L={code:{type:"string",required:!0,message:F("googleAuthenticator.text.tip3"),trigger:["blur"]},password:{type:"string",required:!0,message:F("googleAuthenticator.text.tip4"),trigger:["blur"]}};async function O(){try{await P.value.validate();const{error:e,message:t}=await x(J);await m({title:t,icon:e?"none":"success"}),e||(await I.refreshUserinfo(),g())}catch(e){e.length&&await m({title:e[0].message,icon:"none"})}}return async function(){const{error:e,data:t,message:o}=await p();e?await m({title:o,icon:"none"}):(B.value=t,J.secret=t.secret)}(),(e,t)=>{const o=y(s("uni-nav-bar"),j),a=y(s("up-image"),v),p=y(s("uni-icons"),k),m=d,x=y(s("uni-section"),A),U=y(s("up-input"),V),F=y(s("up-form-item"),q),I=y(s("up-form"),$),R=y(s("up-button"),C);return r(),l(u,null,[i(o,{title:e.$t("page.title.googleAuthenticator"),"status-bar":"","left-icon":"left","background-color":"#F8F8F8",fixed:"",border:!1,onClickLeft:n(g)},null,8,["title","onClickLeft"]),i(m,{class:"body p-30"},{default:c((()=>[i(x,{class:"column-align-center",title:e.$t("googleAuthenticator.text.tip1"),type:"line"},{default:c((()=>[i(m,{class:"column-align-center"},{default:c((()=>[i(a,{"show-loading":!0,src:B.value.qrcod_url,width:"260rpx",height:"260rpx"},null,8,["src"]),i(m,{class:"mt-30"},{default:c((()=>[f(_(B.value.secret)+" ",1),i(p,{"custom-prefix":"fc-iconfont",type:"icon-copy",onClick:t[0]||(t[0]=e=>n(b)(B.value.secret))})])),_:1})])),_:1})])),_:1},8,["title"]),i(x,{class:"column-align-center mt-30",title:e.$t("googleAuthenticator.text.tip2"),type:"line"},null,8,["title"]),i(m,{class:"form"},{default:c((()=>[i(I,{class:"plr-20","label-width":"120",labelPosition:"top",model:J,rules:L,ref_key:"formRef",ref:P},{default:c((()=>[i(F,{required:"","left-icon":"lock",label:e.$t("common.text.verificationCode"),prop:"code",borderBottom:""},{default:c((()=>[i(U,{modelValue:J.code,"onUpdate:modelValue":t[1]||(t[1]=e=>J.code=e),placeholder:L.code.message},null,8,["modelValue","placeholder"])])),_:1},8,["label"]),B.value.secret?(r(),h(F,{key:0,required:"","left-icon":"lock",label:e.$t("googleAuthenticator.text.loginPassword"),prop:"password",borderBottom:""},{default:c((()=>[i(U,{modelValue:J.password,"onUpdate:modelValue":t[2]||(t[2]=e=>J.password=e),placeholder:L.password.message},null,8,["modelValue","placeholder"])])),_:1},8,["label"])):w("",!0)])),_:1},8,["model"]),i(R,{class:"mt-30",color:"rgb(255, 69, 0)",text:e.$t("googleAuthenticator.text.confirmBinding"),onClick:O},null,8,["text"])])),_:1})])),_:1})],64)}}}),[["__scopeId","data-v-81dc8748"]]);export{F as default};
