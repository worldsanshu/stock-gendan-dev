import{R as e,V as a,aC as t,U as n,x as l,D as o,H as r,Y as i,z as c,F as s,ci as u,q as d,cf as m,B as p,C as f,Z as b}from"./index-hU8OKPmH.js";import{_ as g}from"./uni-nav-bar.U3Cmk77Z.js";import{r as x}from"./uni-app.es.DADla7ur.js";import{_ as v}from"./u-input.CvbCMzA9.js";import{_ as k,a as y}from"./u-form.Cwk_KWaU.js";import{_ as h}from"./u-button.CBnbv77V.js";import{_ as C}from"./u-picker.DlUJgiKk.js";import{l as w}from"./wallet.VcNaQDUL.js";import{_ as T}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.DgCYXD5d.js";import"./uni-status-bar.BC4SStPU.js";import"./u-icon.BX8bEo6J.js";import"./u-line.ILQrRGaQ.js";import"./u-loading-icon.DGLmxP69.js";import"./u-popup.jL_Gilo3.js";import"./u-transition.hFcAyfbx.js";import"./u-status-bar.RbmHkU6v.js";const _=T(Object.assign({name:"user-center-wallet-management-add-bank"},{__name:"user-center-wallet-management-add-bank",setup(T){const{t:_}=e(),j=a([]),V=a([]),$=a([[]]),B=t((()=>[j.value,V.value])),q=a(!1),U=a(!1),R=a(!1),P=a(),D=n({card:"",province:"",city:"",bank:"",bankText:"",cityText:"",branch:""}),F={bank:{type:"string",required:!0,message:_("请选择开户银行"),trigger:["blur","change"]},card:[{type:"string",required:!0,message:_("addBankCard.text.tip1"),trigger:["blur","change"]},{validator:(e,a,t)=>{if(/^[0-9]{16,19}$/.test(a))return!0;t(_("addBankCard.text.tip2"))},trigger:["change","blur"]}],cityText:{type:"string",required:!0,message:_("addBankCard.text.tip3"),trigger:["blur","change"]},bankText:{type:"string",required:!0,message:_("addBankCard.text.tip4"),trigger:["blur","change"]},branch:{type:"string",required:!0,message:_("addBankCard.text.tip5"),trigger:["blur","change"]}};const H={};async function I(e){e.value[1]&&e.value[0].id===e.value[1].reid||(R.value=!0,await async function(e){if(H[e])return void(V.value=H[e]);const{error:a,data:t,message:n}=await u({reid:e});a?await d({title:n,icon:"none"}):(H[e]=t,V.value=t)}(e.value[0].id),R.value=!1)}function L(e){const[{name:a,id:t},{name:n,id:l}]=e.value;D.cityText=a===n?a:`${a}-${n}`,D.province=t,D.city=l,q.value=!1}function N(e){D.bankText=e.value[0].name,D.bank=e.value[0].id,U.value=!1}async function O(){try{await P.value.validate();const{error:e,message:a}=await w(D);await d({title:a,icon:e?"none":"success",duration:1e3}),e||setTimeout(b,1e3)}catch(e){e.length&&await d({title:e[0].message,icon:"none"})}}return async function(){const{error:e,data:a,message:t}=await u({reid:1});e?await d({title:t,icon:"none"}):j.value=a}(),async function(){const{error:e,message:a,data:t}=await m();e?await d({title:a,icon:"none"}):$.value=[t.bank]}(),(e,a)=>{const t=x(l("uni-nav-bar"),g),n=x(l("up-input"),v),u=x(l("up-form-item"),k),d=x(l("up-form"),y),m=x(l("up-button"),h),w=x(l("up-picker"),C),T=p;return f(),o(s,null,[r(t,{title:e.$t("page.title.addBankCard"),"status-bar":"","background-color":"#ff3c36",color:"#fff","left-icon":"left",fixed:"",border:!1,onClickLeft:i(b)},null,8,["title","onClickLeft"]),r(T,{class:"body"},{default:c((()=>[r(d,{"border-bottom":"",class:"form","label-width":"120",labelPosition:"top",model:D,rules:F,ref_key:"formRef",ref:P},{default:c((()=>[r(u,{required:"",label:e.$t("addBankCard.text.bankAccount"),prop:"bank"},{default:c((()=>[r(n,{type:"text",modelValue:D.bankText,"onUpdate:modelValue":a[0]||(a[0]=e=>D.bankText=e),placeholder:F.bankText.message,"disabled-color":"#fff","placeholder-style":"color: '#333'","suffix-icon":"arrow-down",onClick:a[1]||(a[1]=e=>U.value=!0)},null,8,["modelValue","placeholder"])])),_:1},8,["label"]),r(u,{required:"",label:e.$t("addBankCard.text.bankCardNumber"),prop:"card"},{default:c((()=>[r(n,{type:"text",modelValue:D.card,"onUpdate:modelValue":a[2]||(a[2]=e=>D.card=e),placeholder:F.card[0].message},null,8,["modelValue","placeholder"])])),_:1},8,["label"]),r(u,{required:"",label:e.$t("addBankCard.text.accountOpeningCity"),prop:"cityText",onClick:a[4]||(a[4]=e=>q.value=!0)},{default:c((()=>[r(n,{type:"text",modelValue:D.cityText,"onUpdate:modelValue":a[3]||(a[3]=e=>D.cityText=e),placeholder:F.cityText.message,"disabled-color":"#fff","placeholder-style":"color: '#333'","suffix-icon":"arrow-down"},null,8,["modelValue","placeholder"])])),_:1},8,["label"]),r(u,{required:"",label:e.$t("addBankCard.text.branchName"),prop:"branch"},{default:c((()=>[r(n,{type:"text",modelValue:D.branch,"onUpdate:modelValue":a[5]||(a[5]=e=>D.branch=e),placeholder:F.branch.message},null,8,["modelValue","placeholder"])])),_:1},8,["label"])])),_:1},8,["model"]),r(m,{class:"mt-30",color:"rgb(255, 69, 0)",text:e.$t("common.text.submit"),onClick:O},null,8,["text"]),r(w,{show:q.value,loading:R.value,"close-on-click-overlay":"",ref:"uPickerRef","key-name":"name",columns:B.value,cancelText:e.$t("common.text.cancel"),confirmText:e.$t("common.text.confirm"),onCancel:a[6]||(a[6]=e=>q.value=!1),onConfirm:L,onChange:I,onClose:a[7]||(a[7]=e=>q.value=!1)},null,8,["show","loading","columns","cancelText","confirmText"]),r(w,{show:U.value,loading:R.value,"close-on-click-overlay":"",ref:"uPickerRef","key-name":"name",columns:$.value,cancelText:e.$t("common.text.cancel"),confirmText:e.$t("common.text.confirm"),onCancel:a[8]||(a[8]=e=>U.value=!1),onConfirm:N,onClose:a[9]||(a[9]=e=>U.value=!1)},null,8,["show","loading","columns","cancelText","confirmText"])])),_:1})],64)}}}),[["__scopeId","data-v-bb57a8b8"]]);export{_ as default};
