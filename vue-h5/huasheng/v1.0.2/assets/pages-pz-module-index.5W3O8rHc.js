import{T as e,Z as t,q as o,r as a,x as n,y as i,z as s,A as r,C as l,H as m,D as u,E as c,F as d,G as h,I as p,B as f,J as _}from"./index-DQ1rtinv.js";import{_ as y}from"./uni-nav-bar.DjBb34GI.js";import{r as b}from"./uni-app.es.Cjy5VdAU.js";import{_ as w}from"./u-tabs.Cw-8ye8Y.js";import{_ as k}from"./u-input.CXlVRvL9.js";import{_ as g,a as R}from"./u-form.C65TemM1.js";import{_ as T}from"./u-picker.C8bS_5G3.js";import{_ as F}from"./u-modal.1yJwVORo.js";import{g as A,a as U,b as j}from"./pz.D62GIeZL.js";import{_ as v}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.CVC5mL0W.js";import"./uni-status-bar.CY3EcjAi.js";import"./u-icon.CGC9KXiC.js";import"./u-line.B0mzGBy-.js";import"./u-loading-icon.CWztmby-.js";import"./u-popup.DEFDGEZO.js";import"./u-transition.ZwOiaRHn.js";import"./u-status-bar.BYA15S5R.js";const C=v({data:()=>({list:[{name:"按天配资"},{name:"按周配资"},{name:"按月配资"}],form:{type:0,account_money:"",deposit_money:"",borrow_duration:"",borrow_duration_str:"",multiple:""},show:!1,verifiedState:!1,borrowDurationPlaceholder:"",useTimeList:[],rateAList:[],dayUseTime:[],dayRateA:[],weekUseTime:[],weekRateA:[],monthUseTime:[],monthRateA:[],moneyRange:[]}),onLoad(e){this.form.type=parseInt(e.index),this.getStockDayFn(),this.getStockWeekFn(),this.getStockMonthFn()},onShow(){0===e().userinfo.id_auth&&(this.verifiedState=!0)},methods:{back:t,async getStockDayFn(){const e=await A();e.data&&(this.form.account_money=e.data.account_money,this.dayUseTime=e.data.day_use_time,this.dayRateA=e.data.day_rate_a,this.moneyRange=e.data.money_range,this.handleUseTimeRateAFn())},async getStockWeekFn(){const e=await U();e.data&&(this.form.account_money=e.data.account_money,this.weekUseTime=e.data.week_use_time,this.weekRateA=e.data.week_rate_a,this.moneyRange=e.data.money_range,this.handleUseTimeRateAFn())},async getStockMonthFn(){const e=await j();e.data&&(this.form.account_money=e.data.account_money,this.monthUseTime=e.data.month_use_time,this.monthRateA=e.data.month_rate_a,this.moneyRange=e.data.money_range,this.handleUseTimeRateAFn())},tabsClick(e){this.form.type=e.index,this.handleUseTimeRateAFn()},handleUseTimeRateAFn(){const e=this.form.type;this.form.deposit_money="",this.form.borrow_duration="",this.form.borrow_duration_str="",this.form.multiple="",0===e&&(this.borrowDurationPlaceholder="操盘期限介于2 - 30 天之间",this.handleDataFn("dayUseTime","dayRateA")),1===e&&(this.borrowDurationPlaceholder="操盘期限介于1 - 10周",this.handleDataFn("weekUseTime","weekRateA")),2===e&&(this.borrowDurationPlaceholder="操盘期限介于1 - 12月之间",this.handleDataFn("monthUseTime","monthRateA"))},handleDataFn(e,t){this.rateAList=this[t];let o=[];this[e].forEach((t=>{o.push({value:t,label:"dayUseTime"===e?`${t}天`:"weekUseTime"===e?`${t}周`:`${t}月`})})),this.useTimeList=[o]},confirm(e){this.form.borrow_duration=e.value[0].value,this.form.borrow_duration_str=e.value[0].label,this.show=!1},onSelectMultipleFn(e){this.form.multiple=e},onSkipPageFn(){const e=this.form;return e.deposit_money?e.deposit_money<parseInt(this.moneyRange[0])?(o({title:`保证金不能少于${this.moneyRange[0]}!`,icon:"none",duration:1500}),!1):e.deposit_money>parseInt(this.moneyRange[1])?(o({title:`保证金不能大于${this.moneyRange[1]}!`,icon:"none",duration:1500}),!1):e.deposit_money%parseInt(this.moneyRange[2])!=0?(o({title:`保金额需为${this.moneyRange[2]}的整数倍!`,icon:"none",duration:1500}),!1):e.borrow_duration?e.multiple?void a({url:`/pages/pz-module/verify-apply-for?oItem=${JSON.stringify(e)}`}):(o({title:"请选择配资金额!",icon:"none",duration:1500}),!1):(o({title:"请选择操盘期限!",icon:"none",duration:1500}),!1):(o({title:"请输入有限保证金!",icon:"none",duration:1500}),!1)},onVerifiedPageFn(e){a({url:e})}}},[["render",function(e,t,o,a,A,U){const j=b(n("uni-nav-bar"),y),v=b(n("up-tabs"),w),C=b(n("up-input"),k),S=b(n("up-form-item"),g),x=l,D=b(n("up-form"),R),$=b(n("up-picker"),T),L=b(n("up-modal"),F);return i(),s(x,null,{default:r((()=>[m(j,{title:"配资操作","status-bar":"","background-color":"#ff3c36",color:"#fff","left-icon":"left",onClickLeft:U.back},null,8,["onClickLeft"]),m(v,{list:A.list,onClick:U.tabsClick,current:A.form.type,lineColor:"#FD4630",lineWidth:"30",scrollable:!1,inactiveStyle:{color:"#979797","font-size":"27rpx","font-weight":"400"},activeStyle:{color:"#FD4630","font-weight":"500","font-size":"31rpx"}},null,8,["list","onClick","current"]),m(D,{class:"form-wrapper","label-width":"200",labelPosition:"top",model:A.form,ref:"formRef"},{default:r((()=>[m(S,{label:"请输入保证金金额",borderBottom:""},{default:r((()=>[m(C,{type:"number",modelValue:A.form.deposit_money,"onUpdate:modelValue":t[0]||(t[0]=e=>A.form.deposit_money=e),placeholder:`保证金介于${A.moneyRange[0]||""} - ${A.moneyRange[1]||""}元之间`},null,8,["modelValue","placeholder"])])),_:1}),m(S,{label:"请选择操盘期限",borderBottom:""},{default:r((()=>[m(C,{modelValue:A.form.borrow_duration_str,"onUpdate:modelValue":t[1]||(t[1]=e=>A.form.borrow_duration_str=e),onClick:t[2]||(t[2]=e=>A.show=!0),placeholder:A.borrowDurationPlaceholder},null,8,["modelValue","placeholder"])])),_:1}),m(S,{label:"请选择配资金额",borderBottom:""},{default:r((()=>[A.rateAList.length?(i(),s(x,{key:0,class:"multiple-view"},{default:r((()=>[(i(!0),u(d,null,c(A.rateAList,((e,t)=>(i(),s(x,{class:f([{active:A.form.multiple===e},"item"]),onClick:t=>U.onSelectMultipleFn(e),key:t},{default:r((()=>[p("配资金额："+_(e)+"倍",1)])),_:2},1032,["class","onClick"])))),128))])),_:1})):h("",!0)])),_:1})])),_:1},8,["model"]),m(x,{class:"btn-wrapper"},{default:r((()=>[m(x,{onClick:U.onSkipPageFn,class:"btn"},{default:r((()=>[p("下一步")])),_:1},8,["onClick"])])),_:1}),m($,{show:A.show,closeOnClickOverlay:!0,cancelText:e.$t("common.text.cancel"),confirmText:e.$t("common.text.confirm"),columns:A.useTimeList,onCancel:t[3]||(t[3]=e=>A.show=!1),onConfirm:U.confirm,"key-name":"label"},null,8,["show","cancelText","confirmText","columns","onConfirm"]),m(L,{show:A.verifiedState,title:"温馨提示",content:"请先进行实名认证",confirmText:"知道了",onConfirm:t[4]||(t[4]=e=>U.onVerifiedPageFn("/pages/user-center-profile-real-name-authentication/user-center-profile-real-name-authentication"))},null,8,["show"])])),_:1})}],["__scopeId","data-v-8c43ff38"]]);export{C as default};
