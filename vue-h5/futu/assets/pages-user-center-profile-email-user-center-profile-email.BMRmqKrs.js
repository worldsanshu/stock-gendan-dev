import{R as e,T as a,V as t,U as i,x as o,y as l,D as r,H as s,Y as n,A as m,F as u,C as c,Z as d,I as p,J as f,q as g,W as b,ay as _,X as x,bG as v}from"./index-CRttwwij.js";import{_ as j}from"./uni-nav-bar.QMl_o85J.js";import{r as w}from"./uni-app.es.otF8raCd.js";import{_ as h}from"./u-input.Dk95kOEY.js";import{_ as y,a as C}from"./u-form.MmCO-oEw.js";import{_ as k}from"./u-button.DTOkec6s.js";import{_ as V}from"./u-code.CkblZ386.js";import{_ as $}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.Bz4Sh8gD.js";import"./uni-status-bar.B9okywpV.js";import"./u-icon.CVzxT_4w.js";import"./u-line.C5lrB_C7.js";import"./u-loading-icon.Cvek95z1.js";const q=$(Object.assign({name:"user-center-profile-email"},{__name:"user-center-profile-email",setup($){const{t:q}=e(),F=a(),U=t(),R=i({email:F.userinfo.email,code:""}),B=t(),G=t(),I=t(50),L={email:[{type:"string",required:!0,message:q("mail.text.tip1"),trigger:["blur"]},{validator:(e,a)=>uni.$u.test.email(a),message:q("mail.text.tip2"),trigger:["blur","change"]}],code:{type:"string",required:!0,message:q("mail.text.tip3"),trigger:["blur"]}};function O(){U.value.validateField("email",(async e=>{if(e.length)return void(await g({title:e[0].message,icon:"none"}));if(!B.value.canGetCode)return void(await g({title:q("mail.text.tip4"),icon:"none"}));await b({title:q("mail.text.tip5")});const{error:a,message:t}=await _({email:R.email,captcha:"spew"});a?await g({title:t,icon:"none"}):(x(),a?await g({title:t,icon:"none"}):(await g({title:q("mail.text.tip6"),icon:"none"}),B.value.start()))}))}async function P(){try{await U.value.validate();const{error:e,message:a}=await v(R);await g({title:a,icon:"none",duration:1e3}),e||(await F.refreshUserinfo(),d())}catch(e){e.length&&await g({title:e[0].message,icon:"none"})}}return(e,a)=>{const t=w(o("uni-nav-bar"),j),i=w(o("up-input"),h),g=w(o("up-form-item"),y),b=c,_=w(o("up-form"),C),x=w(o("up-button"),k),v=w(o("up-code"),V);return l(),r(u,null,[s(t,{title:e.$t("page.title.mail"),"status-bar":"","left-icon":"left","background-color":"#F8F8F8",fixed:"",border:!1,onClickLeft:n(d)},null,8,["title","onClickLeft"]),s(b,{class:"body"},{default:m((()=>[s(_,{class:"form","label-width":"120",labelPosition:"top",model:R,rules:L,ref_key:"formRef",ref:U},{default:m((()=>[s(g,{required:"","left-icon":"email",label:e.$t("mail.text.yourMailbox"),prop:"email",borderBottom:"",ref:"item1"},{default:m((()=>[s(i,{modelValue:R.email,"onUpdate:modelValue":a[0]||(a[0]=e=>R.email=e),placeholder:L.email[0].message},null,8,["modelValue","placeholder"])])),_:1},8,["label"]),s(g,{required:"","left-icon":"lock",label:e.$t("common.text.verificationCode"),prop:"code",borderBottom:"",ref:"item1"},{default:m((()=>[s(i,{type:"password",modelValue:R.code,"onUpdate:modelValue":a[1]||(a[1]=e=>R.code=e),placeholder:L.code.message},{suffix:m((()=>[s(b,{class:"font-12-blue",onClick:O},{default:m((()=>[p(f(G.value),1)])),_:1})])),_:1},8,["modelValue","placeholder"])])),_:1},8,["label"])])),_:1},8,["model"]),s(x,{class:"mt-100",color:"rgb(255, 69, 0)",text:e.$t("common.text.submit"),onClick:P},null,8,["text"]),s(v,{seconds:I.value,ref_key:"emailCodeRef",ref:B,onChange:a[2]||(a[2]=e=>G.value=e)},null,8,["seconds"])])),_:1})],64)}}}),[["__scopeId","data-v-6919b057"]]);export{q as default};
