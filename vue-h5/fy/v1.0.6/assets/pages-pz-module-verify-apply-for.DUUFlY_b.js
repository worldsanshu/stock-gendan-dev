import{Z as a,v as t,q as l,r as e,x as s,y as o,z as n,B as c,C as u,H as i,I as d,J as r,G as f,O as m}from"./index-eZLOZRr1.js";import{_ as p}from"./uni-nav-bar.D0OBKF7O.js";import{r as _}from"./uni-app.es.Cg_AhWrk.js";import{_ as b,a as h}from"./u-checkbox-group.C1RIxcG-.js";import{_ as k}from"./u-modal.DdG9NuAF.js";import{h as x,c as g}from"./pz.CZtIizLj.js";import{_ as y}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.DZmOUZ9v.js";import"./uni-status-bar.dIgvpYbO.js";import"./u-icon.Cinv0AqG.js";import"./u-line.Dqz3ABQq.js";import"./u-loading-icon.C9PbYIxd.js";import"./u-popup.DtQu66jM.js";import"./u-transition.CWuZPti4.js";import"./u-status-bar.Dys_UifE.js";const j=y({data:()=>({form:null,borrow_duration:null,glf:null,paynum:null,callBackObj:null,aloneChecked:["同意用户协议与隐私条款"],show:!1,message:"",glfbl:null}),onLoad(a){a.oItem&&(this.form=JSON.parse(a.oItem)),this.handleApplySaveFn()},methods:{back:a,async handleApplySaveFn(){this.form.type+=1,delete this.form.borrow_duration_str;const a=await x(this.form);if(a.data){let t=null;this.glfbl=a.data.rate,1===a.data.type?t="天":2===a.data.type?t="周":3===a.data.type&&(t="月"),this.borrow_duration=a.data.borrow_duration+t,this.glf=(a.data.borrow_money*a.data.borrow_duration*this.glfbl/100).toFixed(2),this.paynum=parseFloat(a.data.deposit_money)+parseFloat(this.glf),this.callBackObj=a.data}},checkboxChangeFn(a){this.aloneChecked=a},async handleApplySaveSubFn(){if(0===this.aloneChecked.length)return t({title:"请先阅读并同意《实盘交易平台操盘协议》",icon:"none",duration:1500}),!1;const a=await g(this.form);this.message=a.message,this.show=!0},onSkipPageFn(){l({url:"/pages/pz-module/protocol"})},onSwitchTabFn(){e({url:"/pages/index/index"})}}},[["render",function(a,t,l,e,x,g){const y=_(s("uni-nav-bar"),p),j=c,w=m,C=_(s("up-checkbox"),b),F=_(s("up-checkbox-group"),h),B=_(s("up-modal"),k);return u(),o(j,null,{default:n((()=>[i(y,{title:"确认申请","status-bar":"","background-color":"#ff3c36",color:"#fff","left-icon":"left",onClickLeft:g.back},null,8,["onClickLeft"]),x.callBackObj?(u(),o(j,{key:0,class:"content-wrapper"},{default:n((()=>[i(j,{class:"top-bg"}),i(j,{class:"content-box"},{default:n((()=>[i(j,{class:"amount-wrapper"},{default:n((()=>[i(j,{class:"amount-box"},{default:n((()=>[i(j,{class:"item"},{default:n((()=>[i(j,{class:"title"},{default:n((()=>[d("总操盘资金")])),_:1}),i(j,{class:"num"},{default:n((()=>[d(r(x.callBackObj.init_money.toFixed(2))+"元",1)])),_:1})])),_:1}),i(j,{class:"item"},{default:n((()=>[i(j,{class:"title"},{default:n((()=>[d("保证金")])),_:1}),i(j,{class:"num"},{default:n((()=>[d(r(x.callBackObj.deposit_money.toFixed(2))+"元",1)])),_:1})])),_:1}),i(j,{class:"item"},{default:n((()=>[i(j,{class:"title"},{default:n((()=>[d("配资金额")])),_:1}),i(j,{class:"num"},{default:n((()=>[d(r(x.callBackObj.borrow_money.toFixed(2))+"元",1)])),_:1})])),_:1})])),_:1})])),_:1}),i(j,{class:"tab-wrapper"},{default:n((()=>[i(j,{class:"item"},{default:n((()=>[i(j,{class:"title"},{default:n((()=>[d("预警线")])),_:1}),i(j,{class:"text"},{default:n((()=>[i(w,null,{default:n((()=>[d(r((x.callBackObj.borrow_money+.5*x.callBackObj.deposit_money).toFixed(2))+"元",1)])),_:1}),d("(配资资金+保证金 x 50%)")])),_:1})])),_:1}),i(j,{class:"item"},{default:n((()=>[i(j,{class:"title"},{default:n((()=>[d("平仓线")])),_:1}),i(j,{class:"text"},{default:n((()=>[i(w,null,{default:n((()=>[d(r((x.callBackObj.borrow_money+.2*x.callBackObj.deposit_money).toFixed(2))+"元",1)])),_:1}),d("(配资资金+保证金 x 20%)")])),_:1})])),_:1}),i(j,{class:"item"},{default:n((()=>[i(j,{class:"title"},{default:n((()=>[d("操盘时间")])),_:1}),i(j,{class:"text"},{default:n((()=>[i(w,null,{default:n((()=>[d(r(x.borrow_duration),1)])),_:1}),d("(默认开启到期自动续期)")])),_:1})])),_:1}),i(j,{class:"item"},{default:n((()=>[i(j,{class:"title"},{default:n((()=>[d("配资管理费")])),_:1}),i(j,{class:"text"},{default:n((()=>[i(w,null,{default:n((()=>[d(r(x.glf)+"元",1)])),_:1}),d("(配资资金 x 操盘时间 x "+r(x.glfbl)+"%)",1)])),_:1})])),_:1}),i(j,{class:"item"},{default:n((()=>[i(j,{class:"title"},{default:n((()=>[d("操盘须知")])),_:1}),i(j,{class:"text"},{default:n((()=>[d("单只股票最大持仓比例为 100%")])),_:1})])),_:1}),i(j,{class:"item"},{default:n((()=>[i(j,{class:"title"},{default:n((()=>[d("账户余额")])),_:1}),i(j,{class:"text"},{default:n((()=>[i(w,null,{default:n((()=>[d(r(x.form.account_money)+"元",1)])),_:1})])),_:1})])),_:1}),i(j,{class:"item"},{default:n((()=>[i(j,{class:"title"},{default:n((()=>[d("确认支付")])),_:1}),i(j,{class:"text"},{default:n((()=>[d(r(x.paynum)+"元",1)])),_:1})])),_:1})])),_:1}),i(F,{modelValue:x.aloneChecked,"onUpdate:modelValue":t[0]||(t[0]=a=>x.aloneChecked=a),class:"protocol-wrapper",placement:"column",onChange:g.checkboxChangeFn},{default:n((()=>[i(C,{customStyle:{marginBottom:"8px"},label:"同意用户协议与隐私条款",name:"同意用户协议与隐私条款"}),i(w,{onClick:g.onSkipPageFn},{default:n((()=>[d("《实盘交易平台操盘协议》")])),_:1},8,["onClick"])])),_:1},8,["modelValue","onChange"])])),_:1})])),_:1})):f("",!0),i(j,{class:"btn-wrapper"},{default:n((()=>[i(j,{onClick:g.handleApplySaveSubFn,class:"btn"},{default:n((()=>[d("提交申请信息")])),_:1},8,["onClick"])])),_:1}),i(B,{show:x.show,title:"温馨提示",content:x.message,confirmText:"知道了",onConfirm:g.onSwitchTabFn},null,8,["show","content","onConfirm"])])),_:1})}],["__scopeId","data-v-9c58ad35"]]);export{j as default};
