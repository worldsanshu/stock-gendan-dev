import{bt as a,q as e,r as l,x as s,y as t,z as o,B as i,C as n,H as r,I as u,J as c,O as d}from"./index-hU8OKPmH.js";import{_ as f}from"./u-icon.BX8bEo6J.js";import{r as _}from"./uni-app.es.DADla7ur.js";import{_ as m}from"./u-image.hr0f9LKz.js";import{_ as p}from"./u-popup.jL_Gilo3.js";import{_ as h}from"./u-input.CvbCMzA9.js";import{_ as x}from"./u-modal.D43VhWrL.js";import{_ as w}from"./u-picker.DlUJgiKk.js";import{i as g,T as b}from"./index.DUzjlh-0.js";import{g as y}from"./wallet.VcNaQDUL.js";import{_ as C}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./u-transition.hFcAyfbx.js";import"./u-status-bar.RbmHkU6v.js";import"./u-line.ILQrRGaQ.js";import"./u-loading-icon.DGLmxP69.js";const z=C({data:()=>({show:!1,show1:!1,show2:!1,show3:!1,value:"",paywd:"",name:"",id:"",min_account:"",allList:"",mone:""}),mounted(){this.getInve()},methods:{getInve(){g({}).then((a=>{this.allList=a.data})),y({}).then((a=>{this.mone=a.data}))},handleJumpBack(){a()},confirm3(a){console.log(a.value),this.name=a.value[0].name,this.min_account="最小购买金额"+a.value[0].min_account,this.id=a.value[0].id,this.show3=!1},confirm2(){this.show2=!1,b({money:this.value,paywd:this.paywd,interest_record_id:this.id?this.id:this.allList.list[0].id}).then((a=>{console.log("接口数据",a.data),e({title:a.message,icon:"none",duration:2e3}),this.getInve()}))},skip(a){l({url:a})}}},[["render",function(a,e,l,g,b,y){const C=_(s("up-icon"),f),z=_(s("up-image"),m),L=i,k=d,v=_(s("up-popup"),p),j=_(s("up-input"),h),B=_(s("up-modal"),x),V=_(s("up-picker"),w);return n(),t(L,{class:"zero-money-page"},{default:o((()=>[r(L,{class:"header-box"},{default:o((()=>[r(C,{name:"arrow-left",size:"38rpx",color:"#fff",style:{"margin-right":"12rpx"},onClick:y.handleJumpBack},null,8,["onClick"]),r(z,{class:"img-box",src:"/static/images/zero-money-logo.png",width:"69rpx",height:"69rpx",style:{margin:"0 auto"}})])),_:1}),r(L,{class:"header-sub-box"},{default:o((()=>[r(L,null,{default:o((()=>[u(c(b.allList.interest_basic_name),1)])),_:1}),r(L,{class:"header-sub-info"},{default:o((()=>[r(z,{src:"/static/images/zero1.png",width:"23rpx",height:"26rpx"}),r(L,{class:"info-title"},{default:o((()=>[u("资金安全保障中")])),_:1})])),_:1})])),_:1}),r(L,{class:"money-content-box"},{default:o((()=>[r(L,{class:"money-header-box"},{default:o((()=>[r(L,{class:"money-header-box-top"},{default:o((()=>[r(L,{class:"money-header-box-top-left"},{default:o((()=>[r(L,{class:"title"},{default:o((()=>[u("账户余额")])),_:1}),r(L,{class:"footer",onClick:e[0]||(e[0]=a=>y.skip("/pages/financial-details/financial-details"))},{default:o((()=>[u("资金明细")])),_:1})])),_:1}),r(k,{class:"day-buy-number"},{default:o((()=>[u("当天购买人数："+c(b.allList.buy_number),1)])),_:1})])),_:1}),r(L,{class:"money-box"},{default:o((()=>[r(L,null,{default:o((()=>[u("¥")])),_:1}),r(L,null,{default:o((()=>[u(c(b.allList.freeze_balance_bao<0?0:b.allList.freeze_balance_bao),1)])),_:1})])),_:1})])),_:1}),r(L,{class:"money-content"},{default:o((()=>[r(L,{class:"list",onClick:e[1]||(e[1]=a=>y.skip("/pages/zero-money-profit/zero-money-profit"))},{default:o((()=>[r(L,{class:"title"},{default:o((()=>[r(L,null,{default:o((()=>[u("7日年化收益率")])),_:1}),r(C,{name:"arrow-right",size:"12rpx",color:"#7C7C7C"})])),_:1}),r(L,{class:"info"},{default:o((()=>[u(c(b.allList.list?b.allList.list[0].interest_rate:0)+"%",1)])),_:1}),r(L,{class:"footer-bar"},{default:o((()=>[r(L,null,{default:o((()=>[u("万份收益")])),_:1}),r(C,{name:"arrow-right",size:"12rpx",color:"#BE691D"})])),_:1})])),_:1}),r(L,{class:"list",onClick:e[2]||(e[2]=a=>y.skip("/pages/accumulative-total/accumulative-total"))},{default:o((()=>[r(L,{class:"title"},{default:o((()=>[r(L,null,{default:o((()=>[u("累计收益")])),_:1}),r(C,{name:"arrow-right",size:"12rpx",color:"#7C7C7C"})])),_:1}),r(L,{class:"info"},{default:o((()=>[u("¥"+c(b.allList.total_interest),1)])),_:1}),r(L,{class:"footer-bar"},{default:o((()=>[r(L,null,{default:o((()=>[u("昨日 ¥"+c(b.allList.yesterday_interest),1)])),_:1})])),_:1})])),_:1})])),_:1}),r(L,{class:"money-footer",onClick:e[3]||(e[3]=a=>b.show2=!0)},{default:o((()=>[u(" 转入 ")])),_:1})])),_:1}),r(z,{src:"/static/images/zero3.png",width:"719rpx",height:"240rpx",style:{"margin-top":"34rpx"}}),r(L,{class:"zero-money-footer"},{default:o((()=>[r(L,{class:"infos"},{default:o((()=>[r(L,{onClick:e[4]||(e[4]=a=>b.show=!0)},{default:o((()=>[u("了解"+c(b.allList.interest_basic_name),1)])),_:1}),r(L,{onClick:e[5]||(e[5]=a=>b.show1=!0)},{default:o((()=>[u("常见问题")])),_:1})])),_:1}),r(L,{class:"tips",onClick:e[6]||(e[6]=a=>b.show=!0)},{default:o((()=>[u(c(b.allList.interest_basic_name)+"，智能理财助手，让财富增值更简单",1)])),_:1})])),_:1}),r(v,{round:10,show:b.show,onClose:e[8]||(e[8]=a=>b.show=!1)},{default:o((()=>[r(L,{class:"zero-popup-box"},{default:o((()=>[r(L,{class:"popup-header"},{default:o((()=>[r(L,{class:"title",style:{"font-weight":"600"}},{default:o((()=>[u("了解"+c(b.allList.interest_basic_name),1)])),_:1}),r(k,null,{default:o((()=>[u("资金转入"+c(b.allList.interest_basic_name)+"，即申购货币基金",1)])),_:1}),r(L,{class:"header-icon-box"},{default:o((()=>[r(C,{onClick:e[7]||(e[7]=a=>b.show=!1),name:"arrow-down",size:"28rpx",color:"#fff",style:{"margin-top":"8rpx","margin-left":"7rpx"}})])),_:1})])),_:1}),r(L,{class:"popup-content"},{default:o((()=>[r(L,{class:"list"},{default:o((()=>[r(z,{src:"/static/images/zero4.png",width:"46rpx",height:"40rpx"}),r(L,{class:"infos-box"},{default:o((()=>[r(L,null,{default:o((()=>[u("有收益")])),_:1}),r(k,null,{default:o((()=>[u("账户余额转入"+c(b.allList.interest_basic_name)+"，享有货币基金收益，被动增长收入。",1)])),_:1})])),_:1})])),_:1}),r(L,{class:"list"},{default:o((()=>[r(z,{src:"/static/images/zero5.png",width:"46rpx",height:"51rpx"}),r(L,{class:"infos-box"},{default:o((()=>[r(L,null,{default:o((()=>[u("自动转出")])),_:1}),r(k,null,{default:o((()=>[u("收益和本金第二天自动转出，透明化的收益展示，让您随时了解资金动态，享受收益增长的愉悦。")])),_:1})])),_:1})])),_:1}),r(L,{class:"list"},{default:o((()=>[r(z,{src:"/static/images/zero6.png",width:"46rpx",height:"46rpx"}),r(L,{class:"infos-box"},{default:o((()=>[r(L,null,{default:o((()=>[u("加入我们")])),_:1}),r(k,null,{default:o((()=>[u("立即体验，加入"+c(b.allList.interest_basic_name)+"，让每一分钱都发挥最大价值！",1)])),_:1})])),_:1})])),_:1})])),_:1}),r(L,{class:"footer"},{default:o((()=>[u(" 市场有风险，投资需谨慎 ")])),_:1})])),_:1})])),_:1},8,["show"]),r(v,{round:10,show:b.show1,onClose:e[10]||(e[10]=a=>b.show1=!1)},{default:o((()=>[r(L,{class:"zero-popup-box"},{default:o((()=>[r(L,{class:"popup-header"},{default:o((()=>[r(L,{class:"title",style:{"font-weight":"600"}},{default:o((()=>[u("常见问题")])),_:1}),r(L,{class:"header-icon-box"},{default:o((()=>[r(C,{onClick:e[9]||(e[9]=a=>b.show1=!1),name:"arrow-down",size:"28rpx",color:"#fff",style:{"margin-top":"8rpx","margin-left":"7rpx"}})])),_:1})])),_:1}),r(L,{class:"popup-content"},{default:o((()=>[r(L,{class:"list"},{default:o((()=>[r(L,{class:"infos-box"},{default:o((()=>[r(L,null,{default:o((()=>[u("1、什么是"+c(b.allList.interest_basic_name)+"。",1)])),_:1}),r(k,null,{default:o((()=>[u(c(b.allList.interest_basic_name)+"是与多家金融机构合作，为用户提供多样化的理财服务平台。",1)])),_:1})])),_:1})])),_:1}),r(L,{class:"list"},{default:o((()=>[r(L,{class:"infos-box"},{default:o((()=>[r(L,null,{default:o((()=>[u("2、转入和转出")])),_:1}),r(k,null,{default:o((()=>[u("转入为用户自主操作转入，次日系统结算收益后并自动转出。")])),_:1})])),_:1})])),_:1}),r(L,{class:"list"},{default:o((()=>[r(L,{class:"infos-box"},{default:o((()=>[r(L,null,{default:o((()=>[u("3、收益规则")])),_:1}),r(k,null,{default:o((()=>[u("转入时请选择收益方式，不同的收益不同，只有结算的时候才能知道具体收益。")])),_:1})])),_:1})])),_:1})])),_:1}),r(L,{class:"footer"},{default:o((()=>[u(" 市场有风险，投资需谨慎 ")])),_:1})])),_:1})])),_:1},8,["show"]),r(B,{show:b.show2,title:a.title,showCancelButton:!0,confirmText:a.$t("common.text.confirm"),cancelText:a.$t("common.text.cancel"),onConfirm:y.confirm2,onCancel:e[14]||(e[14]=a=>b.show2=!1)},{default:o((()=>[r(L,{class:"munsys"},{default:o((()=>[r(j,{placeholder:b.min_account?b.min_account:"最小购买金额"+b.allList.list[0].min_account,type:"number",border:"surround",modelValue:b.value,"onUpdate:modelValue":e[11]||(e[11]=a=>b.value=a)},null,8,["placeholder","modelValue"]),r(j,{placeholder:a.$t("orderRecord.text.tip2"),type:"password",border:"surround",modelValue:b.paywd,"onUpdate:modelValue":e[12]||(e[12]=a=>b.paywd=a),style:{"margin-top":"16rpx"}},null,8,["placeholder","modelValue"]),r(L,{class:"search-bo"},{default:o((()=>[r(L,{class:"actions"},{default:o((()=>[u(" 收益方式: ")])),_:1}),r(L,{class:"select",onClick:e[13]||(e[13]=a=>b.show3=!0)},{default:o((()=>[r(L,{class:"select-siz"},{default:o((()=>[u(c(b.name?b.name:b.allList.list[0].name),1)])),_:1}),r(C,{name:"arrow-right",color:"#5d5d5d",size:"20",style:{"margin-right":"16rpx"}})])),_:1})])),_:1}),r(L,{class:"munsys_zis"},{default:o((()=>[u("可转入金额:"+c(b.mone.money.account)+"元",1)])),_:1}),r(L,{class:"munsys_ziss"},{default:o((()=>[u("注意:您转入的金额，会立即从您的余额中转入您的"+c(b.allList.interest_basic_name)+"中。",1)])),_:1})])),_:1})])),_:1},8,["show","title","confirmText","cancelText","onConfirm"]),r(V,{show:b.show3,columns:[b.allList.list],keyName:"name",onCancel:e[15]||(e[15]=a=>b.show3=!1),onConfirm:y.confirm3},null,8,["show","columns","onConfirm"])])),_:1})}],["__scopeId","data-v-f1ea65c2"]]);export{z as default};
