import{r as e,aq as t,q as a,x as l,y as s,z as i,A as o,C as n,H as u,I as r,J as d,G as c,D as _,E as f,F as m,N as p,O as b,b7 as h,B as y}from"./index-BXDL7Jao.js";import{_ as v}from"./u-navbar.CVvWPYiU.js";import{r as g}from"./uni-app.es.D-m4Z8Qp.js";import{_ as x,a as k}from"./u-checkbox-group.BbZmS-s_.js";import{_ as w}from"./u-empty.72BGxb1L.js";import{v as D,a as C,x as z,y as j}from"./index.DBg7UyVh.js";import{_ as A}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./u-status-bar.D2nhBfyV.js";import"./u-icon.mPs4vGGX.js";const V=A({data:()=>({switc:"",radiolist1:[{name:"",disabled:!1}],checkboxValue1:0,investorDetail:"",investorlist:"",select:null,documentaryAmount:"",infoList:[],titles:"",lastClickTime:0,interval:3e3,mutis:[{name:"普通投资者",buy_five_price:100,buy_one_amount:1,buy_three_price:"2-8层，偶尔4-8层仓",buy_two_amount:"0-4"},{name:"初级合伙人",buy_five_price:1e3,buy_one_amount:2,buy_three_price:"4-10层仓",buy_two_amount:"5-12"},{name:"中级合伙人",buy_five_price:1e3,buy_one_amount:4,buy_three_price:"4-10层仓",buy_two_amount:"13-25"},{name:"高级合伙人",buy_five_price:1e3,buy_one_amount:7,buy_three_price:"4-10层仓",buy_two_amount:"26-49"},{name:"特级合伙人",buy_five_price:1e3,buy_one_amount:10,buy_three_price:"4-10层仓",buy_two_amount:"50-99"},{name:"核心合伙人",buy_five_price:1e3,buy_one_amount:15,buy_three_price:"4-10层仓",buy_two_amount:"100-9999"}]}),methods:{zismu(){e({url:"/pages/trading-record/trading-record"})},service(){e({url:"/pages/service-agreement/service-agreement"})},checkboxChange(e){1==e[0]?this.checkboxValue1=e[0]:this.checkboxValue1=0},onButtonClick(){D({money:this.documentaryAmount,trader_id:this.investorDetail.id,order_type:2,fund_id:this.investorDetail.fund_id,divide_into:this.investorDetail.divide_into}).then((e=>{"买入成功"==e.message?(setTimeout((()=>{t("/pages/record/record",{id:20})}),1e3),a({title:e.message,icon:"none",duration:1e3})):a({title:e.message,icon:"none",duration:2e3})}))},documen(){if(0!=this.checkboxValue1)if(""!=this.documentaryAmount){if(function(e){return e%100==0}(this.documentaryAmount)){const e=Date.now();e-this.lastClickTime>this.interval&&(this.lastClickTime=e,this.onButtonClick())}else a({title:"优投金额必须是100的整数倍",icon:"none",duration:2e3})}else a({title:"请输入优投金额",icon:"none",duration:2e3});else a({title:"请阅读并同意《服务协议》",icon:"none",duration:2e3})},rightClick(){t("/pages/record/record",{id:20})},sele(e){this.select=e},getEarningsClass(e){const t=e.split("%");return t.length>1&&t[0]<0?"down-color":""}},onLoad(e){C().then((e=>{this.switc=e.data.module})),this.titles=e.title,z({id:e.id}).then((e=>{this.investorDetail=e.data.form,this.investorlist=e.data.list})),j({traderid:e.id}).then((e=>{this.infoList=e.data.list}))}},[["render",function(e,t,a,D,C,z){const j=n,A=g(l("up-navbar"),v),V=p,E=b,T=h,B=g(l("up-checkbox"),x),L=g(l("up-checkbox-group"),k),q=g(l("up-empty"),w);return s(),i(j,null,{default:o((()=>[u(A,{title:C.titles,onRightClick:z.rightClick,bgColor:"#f6f6f6",autoBack:!0,rightText:"优投记录",placeholder:!0},{right:o((()=>[u(j,{style:{color:"#CD8026","font-size":"26rpx"}},{default:o((()=>[r("优投记录")])),_:1})])),_:1},8,["title","onRightClick"]),u(j,{class:"follow-detail-card-box"},{default:o((()=>[u(j,{class:"follow-detail-card"},{default:o((()=>[u(j,{class:"header-box"},{default:o((()=>[u(j,null,{default:o((()=>[r("明星导师")])),_:1})])),_:1}),u(j,{style:{"background-color":"#fff","border-radius":"12rpx",border:"1px solid #E9E9E9","margin-top":"-50rpx",overflow:"hidden"}},{default:o((()=>[u(j,{class:"box1"},{default:o((()=>[u(V,{src:C.investorDetail.head_img,mode:""},null,8,["src"]),u(j,{class:"follow-detail-info"},{default:o((()=>[u(j,{class:"follow-detail-title"},{default:o((()=>[u(j,{class:"title"},{default:o((()=>[r(d(C.investorDetail.name),1)])),_:1}),C.investorDetail.level_name||C.investorDetail.experience?(s(),i(j,{key:0,class:"follow-detail-tags"},{default:o((()=>[C.investorDetail.level_name?(s(),i(E,{key:0},{default:o((()=>[r(d(C.investorDetail.level_name),1)])),_:1})):c("",!0),C.investorDetail.experience?(s(),i(E,{key:1},{default:o((()=>[r(d(C.investorDetail.experience),1)])),_:1})):c("",!0)])),_:1})):c("",!0)])),_:1}),u(j,{style:{"font-weight":"400","font-size":"23rpx",color:"#979797"}},{default:o((()=>[r(d(C.investorDetail.explain),1)])),_:1})])),_:1})])),_:1}),u(j,{class:"box2"},{default:o((()=>[u(j,null,{default:o((()=>[u(E,null,{default:o((()=>[r("最低跟额："+d(C.investorDetail.min_money),1)])),_:1}),u(E,null,{default:o((()=>[r("导师抽佣："+d(C.investorDetail.divide_into),1)])),_:1}),u(E,null,{default:o((()=>[r("最高跟额："+d(C.investorDetail.max_money),1)])),_:1})])),_:1})])),_:1})])),_:1})])),_:1})])),_:1}),u(j,{class:"follow-price"},{default:o((()=>[u(j,{class:"follow-price-top"},{default:o((()=>[u(j,{class:"mudsf"},{default:o((()=>[r(" 最低优投金额 ")])),_:1}),u(E,null,{default:o((()=>[r("（必须是100的倍数）")])),_:1})])),_:1}),u(j,{style:{width:"100%",padding:"0 20rpx","box-sizing":"border-box","padding-bottom":"10rpx"}},{default:o((()=>[u(T,{type:"number",placeholder:"请输入优投金额（必须是100的倍数）",modelValue:C.documentaryAmount,"onUpdate:modelValue":t[0]||(t[0]=e=>C.documentaryAmount=e)},null,8,["modelValue"])])),_:1})])),_:1}),u(j,{class:"flsex"},{default:o((()=>[u(L,{placement:"column",onChange:z.checkboxChange},{default:o((()=>[(s(!0),_(m,null,f(C.radiolist1,((e,t)=>(s(),i(B,{customStyle:{marginBottom:"8px"},key:t,label:e.name,name:"1"},null,8,["label"])))),128))])),_:1},8,["onChange"]),u(j,{class:"flsex_lse"},{default:o((()=>[r("已阅读并同意")])),_:1}),u(j,{class:"flsex_rse",onClick:t[1]||(t[1]=e=>z.service())},{default:o((()=>[r("《服务协议》")])),_:1})])),_:1}),u(j,{class:"flex-cdafr",onClick:z.documen},{default:o((()=>[r(" 确认优投 ")])),_:1},8,["onClick"]),u(j,{class:"content-area"},{default:o((()=>[1==C.switc.trader_record?(s(),i(j,{key:0,class:"follow-description"},{default:o((()=>[u(j,{class:"todn"}),u(j,{class:"base-title"},{default:o((()=>[r("导师战绩")])),_:1})])),_:1})):c("",!0),1==C.switc.trader_record?(s(),i(j,{key:1,class:"record"},{default:o((()=>[u(j,{class:"mun"},{default:o((()=>[u(j,{class:"mun-tse"},{default:o((()=>[r("总收益")])),_:1}),u(j,{class:"mun-bse"},{default:o((()=>[r(d(C.investorDetail.total_revenue)+"%",1)])),_:1})])),_:1}),u(j,{class:"mun-se"},{default:o((()=>[u(j,{class:"mun-selse"},{default:o((()=>[u(j,{class:"mun-zis"},{default:o((()=>[r("月收益")])),_:1}),u(j,{class:"mun-zis"},{default:o((()=>[r(d(C.investorDetail.monthly_revenue)+"%",1)])),_:1})])),_:1}),u(j,{class:"mun-selse"},{default:o((()=>[u(j,{class:"mun-zis"},{default:o((()=>[r("仓位")])),_:1}),u(j,{class:"mun-zis"},{default:o((()=>[r(d(C.investorDetail.position)+"%",1)])),_:1})])),_:1}),u(j,{class:"mun-selse"},{default:o((()=>[u(j,{class:"mun-zis"},{default:o((()=>[r("最大回撤")])),_:1}),u(j,{class:"mun-zis"},{default:o((()=>[r(d(C.investorDetail.maximum_rollback)+"%",1)])),_:1})])),_:1}),u(j,{class:"mun-selse"},{default:o((()=>[u(j,{class:"mun-zis"},{default:o((()=>[r("胜率")])),_:1}),u(j,{class:"mun-zis"},{default:o((()=>[r(d(C.investorDetail.win_obbs)+"%",1)])),_:1})])),_:1})])),_:1})])),_:1})):c("",!0),1==C.switc.trader_detailed?(s(),i(j,{key:2,class:"follow-description"},{default:o((()=>[u(j,{class:"todn"}),u(j,{class:"base-title"},{default:o((()=>[r("持仓明细")])),_:1})])),_:1})):c("",!0),1==C.switc.trader_detailed?(s(),i(j,{key:3,class:"table"},{default:o((()=>[u(j,{class:"table-head"},{default:o((()=>[u(E,null,{default:o((()=>[r("当前持仓")])),_:1}),u(E,null,{default:o((()=>[r("成本价")])),_:1}),u(E,null,{default:o((()=>[r("浮动收益")])),_:1}),u(E,null,{default:o((()=>[r("买入/卖出")])),_:1})])),_:1}),C.investorlist.length?(s(),i(j,{key:0,class:"table-row-wrapper"},{default:o((()=>[(s(!0),_(m,null,f(C.investorlist,((e,t)=>(s(),i(j,{key:t,class:"table-row"},{default:o((()=>[u(j,{class:"table-row-span"},{default:o((()=>[r(d(e.stock_name),1)])),_:2},1024),u(j,{class:"table-row-span"},{default:o((()=>[r(d(e.buy_price),1)])),_:2},1024),u(j,{class:y(["table-row-span",z.getEarningsClass(e.floating_ratio)])},{default:o((()=>[r(d(e.floating_ratio),1)])),_:2},1032,["class"]),u(j,{class:"table-row-span"},{default:o((()=>[u(E,null,{default:o((()=>[r(d(e.buy_time),1)])),_:2},1024),u(E,null,{default:o((()=>[r(d(e.sell_time||"--"),1)])),_:2},1024)])),_:2},1024)])),_:2},1024)))),128))])),_:1})):(s(),i(q,{key:1,mode:"data",icon:"/static/empty/empty-data.png"}))])),_:1})):c("",!0)])),_:1}),u(j,{class:"erlay-dialog"})])),_:1})}],["__scopeId","data-v-4b4d4864"]]);export{V as default};
