import{Z as t,q as e,aq as a,x as o,y as s,z as n,A as u,C as l,H as i,I as r,J as c,K as m,bs as d}from"./index-_qVRbLMl.js";import{_ as f}from"./uni-nav-bar.CqtfX-i-.js";import{r as p}from"./uni-app.es.CwLmRD9h.js";import{_ as h}from"./u-icon.WTlR_o2P.js";import{_}from"./u-picker.CYU8phf3.js";import{C as x}from"./index.md09NobF.js";import{_ as b}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.CkUbBeV1.js";import"./uni-status-bar.BBpSB2OF.js";import"./u-loading-icon.C0chiosN.js";import"./u-popup.CU0Onh68.js";import"./u-transition.D61Fk1Af.js";import"./u-status-bar.DVnN7CDW.js";const g=b({data(){return{name:"",id:"",money:"",value:"",show:!1,tIndex:this.$t("fundPurchase.text.balance"),columns:[[this.$t("fundPurchase.text.balance")]],noticeContent:this.$t("fundPurchase.text.lowest"),noticeContent2:this.$t("fundPurchase.text.gettingStarted")}},onLoad(t){this.name=t.name,this.money=t.money,this.id=t.id},methods:{back:t,confirm(t){console.log(t),this.tIndex=t.value[0],this.show=!1},purchase(){x({fund_id:this.id,money:this.value,order_type:1}).then((t=>{"买入成功"===t.message?(e({title:t.message,icon:"none",duration:1e3}),setTimeout((()=>{a("/pages/buying-result/buying-result",{id:t.data.id,estimate:0})}),1e3)):e({title:t.message,icon:"none",duration:2e3})}))}}},[["render",function(t,e,a,x,b,g){const C=p(o("uni-nav-bar"),f),j=l,y=p(o("up-icon"),h),v=d,w=p(o("up-picker"),_);return s(),n(j,null,{default:u((()=>[i(C,{title:t.$t("page.title.fundPurchase"),"status-bar":"","background-color":"#ff3c36",color:"#fff","left-icon":"left",fixed:"",border:!1,onClickLeft:g.back},null,8,["title","onClickLeft"]),i(j,{class:"fund-to"}),i(j,{class:"fund_title"},{default:u((()=>[i(j,{class:"mounds"},{default:u((()=>[r(c(b.name),1)])),_:1})])),_:1}),i(j,{class:"mode_of_arrival",onClick:e[0]||(e[0]=t=>b.show=!0)},{default:u((()=>[i(j,{class:"mud"},{default:u((()=>[i(j,{class:"box1"},{default:u((()=>[i(j,{class:"sti"},{default:u((()=>[r(c(t.$t("fundPurchase.text.paymentMethod")),1)])),_:1})])),_:1}),i(j,{class:"box2"},{default:u((()=>[i(j,{class:"base"},{default:u((()=>[r(c(b.tIndex),1)])),_:1}),i(y,{name:"arrow-right",color:"#A5A5A5",size:"20"})])),_:1})])),_:1})])),_:1}),i(j,{class:"amount_box"},{default:u((()=>[i(j,{class:"amount_box_title"},{default:u((()=>[m("h5",null,c(t.$t("fundPurchase.text.buyAmount")),1)])),_:1}),i(j,{class:"amount_box_bottom"},{default:u((()=>[i(j,{class:"top"},{default:u((()=>[r("￥")])),_:1}),i(v,{type:"text",placeholder:b.noticeContent+b.money+b.noticeContent2,border:"surround",modelValue:b.value,"onUpdate:modelValue":e[1]||(e[1]=t=>b.value=t)},null,8,["placeholder","modelValue"])])),_:1})])),_:1}),i(j,{class:"mundane"},{default:u((()=>[i(j,{class:"unto",onClick:e[2]||(e[2]=t=>g.purchase())},{default:u((()=>[r(c(t.$t("common.text.confirm")),1)])),_:1})])),_:1}),i(w,{show:b.show,cancelText:t.$t("common.text.cancel"),confirmText:t.$t("common.text.confirm"),onCancel:e[3]||(e[3]=t=>b.show=!1),onConfirm:g.confirm,columns:b.columns},null,8,["show","cancelText","confirmText","onConfirm","columns"])])),_:1})}],["__scopeId","data-v-3ba0afa0"]]);export{g as default};
