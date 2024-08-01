import{R as e,V as a,U as t,x as s,y as l,D as o,H as i,Y as r,A as n,F as d,q as u,C as c,Z as p,bP as m}from"./index-DLU8qqff.js";import{_ as f}from"./uni-nav-bar.CoRAz90g.js";import{r as g}from"./uni-app.es.D9aFg0qL.js";import{_ as x}from"./u-input.BSvGCxOR.js";import{_,a as b}from"./u-form.BRj5kSiB.js";import{_ as v}from"./u-upload.BKvFOyA3.js";import{_ as y}from"./uni-section.DduGaPvx.js";import{_ as j}from"./u-button.B345c9Fe.js";import{_ as h}from"./u-picker.7SedsdVU.js";import{d as w,k}from"./wallet.D_z77eby.js";import{_ as T}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.CHpq13ux.js";import"./uni-status-bar.BHywJPZs.js";import"./u-icon.BAs1cCP0.js";import"./u-line.B-8l58Gh.js";import"./u-loading-icon.-zKdzUZA.js";import"./u-popup.D87-l9QU.js";import"./u-transition.Bp5L5bW5.js";import"./u-status-bar.BqFP-SVD.js";const V=T(Object.assign({name:"user-center-wallet-management-add-address"},{__name:"user-center-wallet-management-add-address",setup(T){const{t:V}=e(),A=a(!1),C=a([[]]),L=a(),q=a([]),F=a([]),$=a([]),R=t({id:"",idText:"",alias:"",address:"",payment_code:""}),U={idText:{type:"string",required:!0,message:V("addAddress.text.tip1"),trigger:["blur","change"]},alias:{type:"string",required:!0,message:V("addAddress.text.tip2"),trigger:["blur","change"]},address:{type:"string",required:!0,message:V("addAddress.text.tip3"),trigger:["blur","change"]}};async function P(){if(console.log("110",R),""!=R.address||""!=R.payment_code)try{await L.value.validate();const{error:e,message:a}=await k(R);await u({title:a,icon:e?"none":"success",duration:1e3}),e||setTimeout(p,1e3)}catch(e){e.length&&await u({title:e[0].message,icon:"none"})}else u({title:"请输入地址或上传收款码",icon:"none"})}async function D(e){console.log("e",e.value[0].id),R.id=e.value[0].id,R.idText=e.value[0].name,A.value=!1}return async function(){const{error:e,message:a,data:t}=await w({type:"wallet"});e?await u({title:a,icon:"none"}):C.value=[t.list]}(),(e,a)=>{const t=g(s("uni-nav-bar"),f),w=g(s("up-input"),x),k=g(s("up-form-item"),_),T=g(s("up-form"),b),N=g(s("up-upload"),v),O=c,Y=g(s("uni-section"),y),Z=g(s("up-button"),j),H=g(s("up-picker"),h);return l(),o(d,null,[i(t,{title:e.$t("page.title.addAddress"),"status-bar":"","background-color":"#ff3c36",color:"#fff","left-icon":"left",fixed:"",border:!1,onClickLeft:r(p)},null,8,["title","onClickLeft"]),i(O,{class:"body"},{default:n((()=>[i(T,{"border-bottom":"",class:"form","label-width":"120",labelPosition:"top",model:R,rules:U,ref_key:"formRef",ref:L},{default:n((()=>[i(k,{required:"",label:e.$t("addAddress.text.currency"),prop:"idText"},{default:n((()=>[i(w,{type:"text",modelValue:R.idText,"onUpdate:modelValue":a[0]||(a[0]=e=>R.idText=e),placeholder:U.idText.message,"disabled-color":"#fff","placeholder-style":"color: '#333'","suffix-icon":"arrow-down",onClick:a[1]||(a[1]=e=>A.value=!0)},null,8,["modelValue","placeholder"])])),_:1},8,["label"]),i(k,{required:"",label:e.$t("addAddress.text.aliases"),prop:"alias"},{default:n((()=>[i(w,{type:"text",modelValue:R.alias,"onUpdate:modelValue":a[2]||(a[2]=e=>R.alias=e),placeholder:U.alias.message},null,8,["modelValue","placeholder"])])),_:1},8,["label"]),i(k,{required:"",label:e.$t("addAddress.text.address")},{default:n((()=>[i(w,{type:"text",modelValue:R.address,"onUpdate:modelValue":a[3]||(a[3]=e=>R.address=e),placeholder:U.address.message},null,8,["modelValue","placeholder"])])),_:1},8,["label"])])),_:1},8,["model"]),i(Y,{title:"请上传收款码",type:"line",padding:"30rpx"},{default:n((()=>[i(O,{class:"passport-wrapper"},{default:n((()=>[i(N,{class:"passport-content mt-50",fileList:q.value,onAfterRead:a[4]||(a[4]=e=>async function(e,a){console.log("afterRead120",e);const t="passportFileList"===a?q:"frontFileList"===a?F:$,s="passportFileList"===a?"passport_pic":"frontFileList"===a?"card_pic_hand":"card_pic_back";t.value=[{...e.file,status:"uploading",message:V("verified.text.uploading")}];const l=await m(e.file.url);R.payment_code=l.data.file_path,1===Number(l.status)?(t.value=[{...e.file,status:"success",message:V("verified.text.uploadSuccessFully")}],R.value[s]=l.data.file_path):await u({title:l.message||V("verified.text.uploadFailed"),icon:"none"})}(e,"passportFileList")),onDelete:a[5]||(a[5]=e=>(q.value=[],R.payment_code="")),name:"url",maxCount:1},null,8,["fileList"])])),_:1})])),_:1}),i(Z,{class:"mt-30",color:"rgb(255, 69, 0)",text:e.$t("common.text.submit"),onClick:P},null,8,["text"]),i(H,{show:A.value,"close-on-click-overlay":"",ref:"uPickerRef","key-name":"name",columns:C.value,cancelText:e.$t("common.text.cancel"),confirmText:e.$t("common.text.confirm"),onCancel:a[6]||(a[6]=e=>A.value=!1),onConfirm:D,onClose:a[7]||(a[7]=e=>A.value=!1)},null,8,["show","columns","cancelText","confirmText"])])),_:1})],64)}}}),[["__scopeId","data-v-0a4840ee"]]);export{V as default};
