import{Z as t,v as e,aq as a,x as o,y as s,z as n,B as u,C as l,H as i,I as r,J as c,K as m,bt as d}from"./index-V1zTiwCe.js";import{_ as f}from"./uni-nav-bar.B9quvnCD.js";import{r as p}from"./uni-app.es.CQrvLY-P.js";import{_ as h}from"./u-icon.EexHLkhm.js";import{_}from"./u-picker.CL7jm19O.js";import{C as x}from"./index.C621UsXl.js";import{_ as b}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.DZWFI7QG.js";import"./uni-status-bar.BjedvyN3.js";import"./u-loading-icon.COOKVkTA.js";import"./u-popup.DhY3lYTv.js";import"./u-transition.BCj5qmVK.js";import"./u-status-bar.D4sFBUfg.js";const g=b({data(){return{name:"",id:"",money:"",value:"",show:!1,tIndex:this.$t("fundPurchase.text.balance"),columns:[[this.$t("fundPurchase.text.balance")]],noticeContent:this.$t("fundPurchase.text.lowest"),noticeContent2:this.$t("fundPurchase.text.gettingStarted")}},onLoad(t){this.name=t.name,this.money=t.money,this.id=t.id},methods:{back:t,confirm(t){console.log(t),this.tIndex=t.value[0],this.show=!1},purchase(){x({fund_id:this.id,money:this.value,order_type:1}).then((t=>{"买入成功"===t.message?(e({title:t.message,icon:"none",duration:1e3}),setTimeout((()=>{a("/pages/buying-result/buying-result",{id:t.data.id,estimate:0})}),1e3)):e({title:t.message,icon:"none",duration:2e3})}))}}},[["render",function(t,e,a,x,b,g){const C=p(o("uni-nav-bar"),f),j=u,v=p(o("up-icon"),h),y=d,w=p(o("up-picker"),_);return l(),s(j,null,{default:n((()=>[i(C,{title:t.$t("page.title.fundPurchase"),"status-bar":"","background-color":"#ff3c36",color:"#fff","left-icon":"left",fixed:"",border:!1,onClickLeft:g.back},null,8,["title","onClickLeft"]),i(j,{class:"fund-to"}),i(j,{class:"fund_title"},{default:n((()=>[i(j,{class:"mounds"},{default:n((()=>[r(c(b.name),1)])),_:1})])),_:1}),i(j,{class:"mode_of_arrival",onClick:e[0]||(e[0]=t=>b.show=!0)},{default:n((()=>[i(j,{class:"mud"},{default:n((()=>[i(j,{class:"box1"},{default:n((()=>[i(j,{class:"sti"},{default:n((()=>[r(c(t.$t("fundPurchase.text.paymentMethod")),1)])),_:1})])),_:1}),i(j,{class:"box2"},{default:n((()=>[i(j,{class:"base"},{default:n((()=>[r(c(b.tIndex),1)])),_:1}),i(v,{name:"arrow-right",color:"#A5A5A5",size:"20"})])),_:1})])),_:1})])),_:1}),i(j,{class:"amount_box"},{default:n((()=>[i(j,{class:"amount_box_title"},{default:n((()=>[m("h5",null,c(t.$t("fundPurchase.text.buyAmount")),1)])),_:1}),i(j,{class:"amount_box_bottom"},{default:n((()=>[i(j,{class:"top"},{default:n((()=>[r("￥")])),_:1}),i(y,{type:"text",placeholder:b.noticeContent+b.money+b.noticeContent2,border:"surround",modelValue:b.value,"onUpdate:modelValue":e[1]||(e[1]=t=>b.value=t)},null,8,["placeholder","modelValue"])])),_:1})])),_:1}),i(j,{class:"mundane"},{default:n((()=>[i(j,{class:"unto",onClick:e[2]||(e[2]=t=>g.purchase())},{default:n((()=>[r(c(t.$t("common.text.confirm")),1)])),_:1})])),_:1}),i(w,{show:b.show,cancelText:t.$t("common.text.cancel"),confirmText:t.$t("common.text.confirm"),onCancel:e[3]||(e[3]=t=>b.show=!1),onConfirm:g.confirm,columns:b.columns},null,8,["show","cancelText","confirmText","onConfirm","columns"])])),_:1})}],["__scopeId","data-v-3ba0afa0"]]);export{g as default};
