import{$ as l,q as e,r as a,x as s,y as t,z as o,A as i,C as n,H as r,I as u,J as c,O as d}from"./index-DqmbykwS.js";import{_ as f}from"./u-icon.C8Dzb9bG.js";import{r as m}from"./uni-app.es.Db8KiTPg.js";import{_}from"./u-image.CSxGA87R.js";import{_ as p}from"./u-popup.BkLeDKXk.js";import{_ as h}from"./u-input.BSdiGchX.js";import{_ as x}from"./u-modal.B5d3YsYq.js";import{_ as w}from"./u-picker.DWEadpGD.js";import{i as g,T as b}from"./index.DnXK9ofd.js";import{g as y}from"./wallet.DpjZvsA_.js";import{_ as C}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./u-transition.c1rIVvos.js";import"./u-status-bar.Cd_Smesb.js";import"./u-line.CztHVthy.js";import"./u-loading-icon.B-sVNCXO.js";const z=C({data:()=>({show:!1,show1:!1,show2:!1,show3:!1,value:"",paywd:"",name:"",id:"",min_account:"",allList:"",mone:""}),mounted(){this.getInve()},methods:{getInve(){g({}).then((l=>{this.allList=l.data})),y({}).then((l=>{this.mone=l.data}))},handleJumpBack(){l()},confirm3(l){console.log(l.value),this.name=l.value[0].name,this.min_account="最小购买金额"+l.value[0].min_account,this.id=l.value[0].id,this.show3=!1},confirm2(){this.show2=!1,b({money:this.value,paywd:this.paywd,interest_record_id:this.id?this.id:this.allList.list[0].id}).then((l=>{console.log("接口数据",l.data),e({title:l.message,icon:"none",duration:2e3}),this.getInve()}))},skip(l){a({url:l})}}},[["render",function(l,e,a,g,b,y){const C=m(s("up-icon"),f),z=m(s("up-image"),_),k=n,L=d,j=m(s("up-popup"),p),v=m(s("up-input"),h),V=m(s("up-modal"),x),T=m(s("up-picker"),w);return t(),o(k,{class:"zero-money-page"},{default:i((()=>[r(k,{class:"header-box"},{default:i((()=>[r(C,{name:"arrow-left",size:"38rpx",color:"#fff",style:{"margin-right":"12rpx"},onClick:y.handleJumpBack},null,8,["onClick"]),r(z,{class:"img-box",src:"/static/images/zero-money-logo.png",width:"69rpx",height:"69rpx",style:{margin:"0 auto"}})])),_:1}),r(k,{class:"header-sub-box"},{default:i((()=>[r(k,null,{default:i((()=>[u(c(b.allList.interest_basic_name),1)])),_:1}),r(k,{class:"header-sub-info"},{default:i((()=>[r(z,{src:"/static/images/zero1.png",width:"23rpx",height:"26rpx"}),r(k,{class:"info-title"},{default:i((()=>[u("资金安全保障中")])),_:1})])),_:1})])),_:1}),r(k,{class:"money-content-box"},{default:i((()=>[r(k,{class:"money-header-box"},{default:i((()=>[r(k,{class:"title"},{default:i((()=>[u("账户余额")])),_:1}),r(k,{class:"money-box"},{default:i((()=>[r(k,null,{default:i((()=>[u("¥")])),_:1}),r(k,null,{default:i((()=>[u(c(b.allList.freeze_balance_bao),1)])),_:1})])),_:1}),r(k,{class:"footer",onClick:e[0]||(e[0]=l=>y.skip("/pages/financial-details/financial-details"))},{default:i((()=>[u("资金明细")])),_:1})])),_:1}),r(k,{class:"money-content"},{default:i((()=>[r(k,{class:"list",onClick:e[1]||(e[1]=l=>y.skip("/pages/zero-money-profit/zero-money-profit"))},{default:i((()=>[r(k,{class:"title"},{default:i((()=>[r(k,null,{default:i((()=>[u("7日年化收益率")])),_:1}),r(C,{name:"arrow-right",size:"12rpx",color:"#7C7C7C"})])),_:1}),r(k,{class:"info"},{default:i((()=>[u(c(b.allList.list?b.allList.list[0].interest_rate:0)+"%",1)])),_:1}),r(k,{class:"footer-bar"},{default:i((()=>[r(k,null,{default:i((()=>[u("万份收益")])),_:1}),r(C,{name:"arrow-right",size:"12rpx",color:"#BE691D"})])),_:1})])),_:1}),r(k,{class:"list",onClick:e[2]||(e[2]=l=>y.skip("/pages/accumulative-total/accumulative-total"))},{default:i((()=>[r(k,{class:"title"},{default:i((()=>[r(k,null,{default:i((()=>[u("累计收益")])),_:1}),r(C,{name:"arrow-right",size:"12rpx",color:"#7C7C7C"})])),_:1}),r(k,{class:"info"},{default:i((()=>[u("¥"+c(b.allList.total_interest),1)])),_:1}),r(k,{class:"footer-bar"},{default:i((()=>[r(k,null,{default:i((()=>[u("昨日 ¥"+c(b.allList.yesterday_interest),1)])),_:1})])),_:1})])),_:1})])),_:1}),r(k,{class:"money-footer",onClick:e[3]||(e[3]=l=>b.show2=!0)},{default:i((()=>[u(" 转入 ")])),_:1})])),_:1}),r(z,{src:"/static/images/zero3.png",width:"719rpx",height:"240rpx",style:{"margin-top":"34rpx"}}),r(k,{class:"zero-money-footer"},{default:i((()=>[r(k,{class:"infos"},{default:i((()=>[r(k,{onClick:e[4]||(e[4]=l=>b.show=!0)},{default:i((()=>[u("了解"+c(b.allList.interest_basic_name),1)])),_:1}),r(k,{onClick:e[5]||(e[5]=l=>b.show1=!0)},{default:i((()=>[u("常见问题")])),_:1})])),_:1}),r(k,{class:"tips",onClick:e[6]||(e[6]=l=>b.show=!0)},{default:i((()=>[u(c(b.allList.interest_basic_name)+"，智能理财助手，让财富增值更简单",1)])),_:1})])),_:1}),r(j,{round:10,show:b.show,onClose:e[8]||(e[8]=l=>b.show=!1)},{default:i((()=>[r(k,{class:"zero-popup-box"},{default:i((()=>[r(k,{class:"popup-header"},{default:i((()=>[r(k,{class:"title",style:{"font-weight":"600"}},{default:i((()=>[u("了解"+c(b.allList.interest_basic_name),1)])),_:1}),r(L,null,{default:i((()=>[u("资金转入"+c(b.allList.interest_basic_name)+"，即申购货币基金",1)])),_:1}),r(k,{class:"header-icon-box"},{default:i((()=>[r(C,{onClick:e[7]||(e[7]=l=>b.show=!1),name:"arrow-down",size:"28rpx",color:"#fff",style:{"margin-top":"8rpx","margin-left":"7rpx"}})])),_:1})])),_:1}),r(k,{class:"popup-content"},{default:i((()=>[r(k,{class:"list"},{default:i((()=>[r(z,{src:"/static/images/zero4.png",width:"46rpx",height:"40rpx"}),r(k,{class:"infos-box"},{default:i((()=>[r(k,null,{default:i((()=>[u("有收益")])),_:1}),r(L,null,{default:i((()=>[u("账户余额转入"+c(b.allList.interest_basic_name)+"，享有货币基金收益，被动增长收入。",1)])),_:1})])),_:1})])),_:1}),r(k,{class:"list"},{default:i((()=>[r(z,{src:"/static/images/zero5.png",width:"46rpx",height:"51rpx"}),r(k,{class:"infos-box"},{default:i((()=>[r(k,null,{default:i((()=>[u("自动转出")])),_:1}),r(L,null,{default:i((()=>[u("收益和本金第二天自动转出，透明化的收益展示，让您随时了解资金动态，享受收益增长的愉悦。")])),_:1})])),_:1})])),_:1}),r(k,{class:"list"},{default:i((()=>[r(z,{src:"/static/images/zero6.png",width:"46rpx",height:"46rpx"}),r(k,{class:"infos-box"},{default:i((()=>[r(k,null,{default:i((()=>[u("加入我们")])),_:1}),r(L,null,{default:i((()=>[u("立即体验，加入"+c(b.allList.interest_basic_name)+"，让每一分钱都发挥最大价值！",1)])),_:1})])),_:1})])),_:1})])),_:1}),r(k,{class:"footer"},{default:i((()=>[u(" 市场有风险，投资需谨慎 ")])),_:1})])),_:1})])),_:1},8,["show"]),r(j,{round:10,show:b.show1,onClose:e[10]||(e[10]=l=>b.show1=!1)},{default:i((()=>[r(k,{class:"zero-popup-box"},{default:i((()=>[r(k,{class:"popup-header"},{default:i((()=>[r(k,{class:"title",style:{"font-weight":"600"}},{default:i((()=>[u("常见问题")])),_:1}),r(k,{class:"header-icon-box"},{default:i((()=>[r(C,{onClick:e[9]||(e[9]=l=>b.show1=!1),name:"arrow-down",size:"28rpx",color:"#fff",style:{"margin-top":"8rpx","margin-left":"7rpx"}})])),_:1})])),_:1}),r(k,{class:"popup-content"},{default:i((()=>[r(k,{class:"list"},{default:i((()=>[r(k,{class:"infos-box"},{default:i((()=>[r(k,null,{default:i((()=>[u("1、什么是"+c(b.allList.interest_basic_name)+"。",1)])),_:1}),r(L,null,{default:i((()=>[u(c(b.allList.interest_basic_name)+"是与多家金融机构合作，为用户提供多样化的理财服务平台。",1)])),_:1})])),_:1})])),_:1}),r(k,{class:"list"},{default:i((()=>[r(k,{class:"infos-box"},{default:i((()=>[r(k,null,{default:i((()=>[u("2、转入和转出")])),_:1}),r(L,null,{default:i((()=>[u("转入为用户自主操作转入，次日系统结算收益后并自动转出。")])),_:1})])),_:1})])),_:1}),r(k,{class:"list"},{default:i((()=>[r(k,{class:"infos-box"},{default:i((()=>[r(k,null,{default:i((()=>[u("3、收益规则")])),_:1}),r(L,null,{default:i((()=>[u("转入时请选择收益方式，不同的收益不同，只有结算的时候才能知道具体收益。")])),_:1})])),_:1})])),_:1})])),_:1}),r(k,{class:"footer"},{default:i((()=>[u(" 市场有风险，投资需谨慎 ")])),_:1})])),_:1})])),_:1},8,["show"]),r(V,{show:b.show2,title:l.title,showCancelButton:!0,confirmText:l.$t("common.text.confirm"),cancelText:l.$t("common.text.cancel"),onConfirm:y.confirm2,onCancel:e[14]||(e[14]=l=>b.show2=!1)},{default:i((()=>[r(k,{class:"munsys"},{default:i((()=>[r(v,{placeholder:b.min_account?b.min_account:"最小购买金额"+b.allList.list[0].min_account,type:"number",border:"surround",modelValue:b.value,"onUpdate:modelValue":e[11]||(e[11]=l=>b.value=l)},null,8,["placeholder","modelValue"]),r(v,{placeholder:l.$t("orderRecord.text.tip2"),type:"number",border:"surround",modelValue:b.paywd,"onUpdate:modelValue":e[12]||(e[12]=l=>b.paywd=l),style:{"margin-top":"16rpx"}},null,8,["placeholder","modelValue"]),r(k,{class:"search-bo"},{default:i((()=>[r(k,{class:"actions"},{default:i((()=>[u(" 收益方式: ")])),_:1}),r(k,{class:"select",onClick:e[13]||(e[13]=l=>b.show3=!0)},{default:i((()=>[r(k,{class:"select-siz"},{default:i((()=>[u(c(b.name?b.name:b.allList.list[0].name),1)])),_:1}),r(C,{name:"arrow-right",color:"#5d5d5d",size:"20",style:{"margin-right":"16rpx"}})])),_:1})])),_:1}),r(k,{class:"munsys_zis"},{default:i((()=>[u("可转入金额:"+c(b.mone.money.account)+"元",1)])),_:1}),r(k,{class:"munsys_ziss"},{default:i((()=>[u("注意:您转入的金额，会立即从您的余额中转入您的"+c(b.allList.interest_basic_name)+"中。",1)])),_:1})])),_:1})])),_:1},8,["show","title","confirmText","cancelText","onConfirm"]),r(T,{show:b.show3,columns:[b.allList.list],keyName:"name",onCancel:e[15]||(e[15]=l=>b.show3=!1),onConfirm:y.confirm3},null,8,["show","columns","onConfirm"])])),_:1})}],["__scopeId","data-v-fffc405e"]]);export{z as default};
