import{aq as e,Z as l,q as t,v as s,x as a,y as i,z as o,B as n,C as d,H as c,I as u,J as r,G as f,K as _,D as m,E as p,F as h,N as b,O as x,bt as y,a9 as v,L as g,A as k,M as w}from"./index-eZLOZRr1.js";import{_ as C}from"./u-navbar.CKeItcLv.js";import{r as D}from"./uni-app.es.Cg_AhWrk.js";import{_ as L}from"./u-image.CKDYgjBW.js";import{_ as z,a as I}from"./u-checkbox-group.C1RIxcG-.js";import{_ as j}from"./u-empty.B35TMJs6.js";import{v as V,a as A,x as B,y as T}from"./index.pCJYbHY4.js";import{_ as q}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./u-status-bar.Dys_UifE.js";import"./u-icon.Cinv0AqG.js";import"./u-transition.CWuZPti4.js";const E=q({data:()=>({switc:"",radiolist1:[{name:"",disabled:!1}],checkboxValue1:0,radiovalue1:"",investorDetail:"",investorlist:[],select:null,documentaryAmount:"",infoList:[],id:0,subInfoList:[],titles:"",lastClickTime:0,interval:3e3}),methods:{cuti(){e("/pages/trading-record/trading-record",{id:this.id})},back:l,service(){t({url:"/pages/service-agreement/service-agreement"})},checkboxChange(e){1==e[0]?this.checkboxValue1=e[0]:this.checkboxValue1=0},onButtonClick(){V({money:this.documentaryAmount,cycle:this.subInfoList[this.select].cycle,trader_id:this.investorDetail.id,order_type:3,fund_id:this.investorDetail.fund_id,divide_into:this.subInfoList[this.select].commission}).then((l=>{"买入成功"==l.message?(setTimeout((()=>{e("/pages/record/record",{id:20})}),1e3),s({title:l.message,icon:"none",duration:1e3})):s({title:l.message,icon:"none",duration:2e3})}))},documen(){if(0!=this.checkboxValue1)if(null!=this.select)if(""!=this.documentaryAmount){if(function(e){return e%100==0}(this.documentaryAmount)){const e=Date.now();e-this.lastClickTime>this.interval&&(this.lastClickTime=e,this.onButtonClick())}else s({title:"优投金额必须是100的整数倍",icon:"none",duration:2e3})}else s({title:"请输入优投金额",icon:"none",duration:2e3});else s({title:"请选择交易周期",icon:"none",duration:2e3});else s({title:"请阅读并同意《服务协议》",icon:"none",duration:2e3})},rightClick(){e("/pages/record/record",{id:20})},sele(e){this.select=e,this.infoList=this.subInfoList.filter(((l,t)=>t==e))},getEarningsClass(e){const l=e.split("%");return l.length>1&&l[0]<0?"down-color":""}},onLoad(e){A().then((e=>{this.switc=e.data.module})),this.id=e.id,this.titles=e.title,B({id:e.id}).then((e=>{this.investorDetail=e.data.form,this.investorlist=e.data.list})),T({traderid:e.id}).then((e=>{this.infoList=e.data.list,this.subInfoList=JSON.parse(JSON.stringify(e.data.list)),this.sele(0)}))}},[["render",function(e,l,t,s,V,A){const B=n,T=D(a("up-navbar"),C),q=b,E=x,J=y,N=D(a("up-image"),L),O=D(a("up-checkbox"),z),S=D(a("up-checkbox-group"),I),G=D(a("up-empty"),j);return d(),i(B,null,{default:o((()=>[c(T,{title:V.titles,onRightClick:A.rightClick,bgColor:"#f6f6f6","left-icon":"arrow-left",autoBack:!1,rightText:"优投记录",placeholder:!0,onLeftClick:A.back},{right:o((()=>[c(B,{style:{color:"#CD8026","font-size":"26rpx"}},{default:o((()=>[u("优投记录")])),_:1})])),_:1},8,["title","onRightClick","onLeftClick"]),c(B,{class:"follow-detail-card-box"},{default:o((()=>[c(B,{class:"follow-detail-card"},{default:o((()=>[c(B,{class:"header-box"},{default:o((()=>[c(B,null,{default:o((()=>[u("明星导师 | VIP")])),_:1})])),_:1}),c(B,{style:{"background-color":"#fff","border-radius":"12rpx",border:"1px solid #e9e9e9","margin-top":"-50rpx",overflow:"hidden"}},{default:o((()=>[c(B,{class:"box1"},{default:o((()=>[c(q,{src:V.investorDetail.head_img,mode:""},null,8,["src"]),c(B,{class:"follow-detail-info"},{default:o((()=>[c(B,{class:"follow-detail-title"},{default:o((()=>[c(B,{class:"title"},{default:o((()=>[u(r(V.investorDetail.name),1)])),_:1}),c(B,{class:"follow-detail-tags"},{default:o((()=>[V.investorDetail.level_name?(d(),i(E,{key:0},{default:o((()=>[u(r(V.investorDetail.level_name),1)])),_:1})):f("",!0),V.investorDetail.experience?(d(),i(E,{key:1},{default:o((()=>[u(r(V.investorDetail.experience),1)])),_:1})):f("",!0)])),_:1})])),_:1}),c(B,{style:{"font-weight":"400","font-size":"23rpx",color:"#979797"}},{default:o((()=>[u(r(V.investorDetail.explain),1)])),_:1})])),_:1})])),_:1}),c(B,{class:"box2"},{default:o((()=>[c(B,null,{default:o((()=>[c(E,null,{default:o((()=>[u("最低跟额："+r(V.select||0==V.select?V.subInfoList[V.select].min_money_text:V.investorDetail.min_money),1)])),_:1}),c(E,null,{default:o((()=>[u("导师抽佣："+r(V.select||0==V.select?V.subInfoList[V.select].commission:"0")+"%",1)])),_:1}),c(E,null,{default:o((()=>[u("最高跟额："+r(V.select||0==V.select?V.subInfoList[V.select].max_money_text:V.investorDetail.max_money),1)])),_:1})])),_:1})])),_:1})])),_:1})])),_:1})])),_:1}),c(B,{class:"follow-price"},{default:o((()=>[c(B,{class:"follow-price-top"},{default:o((()=>[c(B,{class:"mudsf"},{default:o((()=>[u(" 最低优投金额 ")])),_:1}),_("span",null,"（必须是100的倍数）")])),_:1}),c(B,{style:{width:"100%",padding:"0 20rpx","box-sizing":"border-box"}},{default:o((()=>[c(J,{type:"number",placeholder:"请输入优投金额",modelValue:V.documentaryAmount,"onUpdate:modelValue":l[0]||(l[0]=e=>V.documentaryAmount=e)},null,8,["modelValue"])])),_:1})])),_:1}),c(B,{class:"vip-follow-cards"},{default:o((()=>[(d(!0),m(h,null,p(V.subInfoList,((e,l)=>(d(),i(B,{class:"vip-follow-card",key:l,onClick:e=>A.sele(l),style:v(V.select==l?"border: 1px solid #e35540;":"")},{default:o((()=>[c(B,{class:"base-card"},{default:o((()=>[c(B,{class:"flex-center"},{default:o((()=>[g(c(B,{class:k(["points",(V.select,"on")])},{default:o((()=>["普通会员"!=e.user_level?(d(),i(N,{key:0,src:"/static/images/edit12.png",width:"25rpx",height:"30rpx"})):f("",!0)])),_:2},1032,["class"]),[[w,1==e.user_level_require]]),c(B,{class:"flex-cenwf"},{default:o((()=>[u(r(e.cycle)+" 个交易日 ",1)])),_:2},1024)])),_:2},1024),c(B,{class:"vip-merchandiszis"},{default:o((()=>[u(r(e.min_money_text)+"~"+r(e.max_money_text),1)])),_:2},1024)])),_:2},1024)])),_:2},1032,["onClick","style"])))),128))])),_:1}),c(B,{class:"flsex"},{default:o((()=>[c(S,{placement:"column",onChange:A.checkboxChange},{default:o((()=>[(d(!0),m(h,null,p(V.radiolist1,((e,l)=>(d(),i(O,{customStyle:{marginBottom:"8px"},key:l,label:e.name,name:"1"},null,8,["label"])))),128))])),_:1},8,["onChange"]),c(B,{class:"flsex_lse"},{default:o((()=>[u("已阅读并同意")])),_:1}),c(B,{class:"flsex_rse",onClick:l[1]||(l[1]=e=>A.service())},{default:o((()=>[u("《服务协议》")])),_:1})])),_:1}),c(B,{class:"flex-cdafr",onClick:A.documen},{default:o((()=>[u(" 一键优投 ")])),_:1},8,["onClick"]),c(B,{class:"vip-follow-desc"},{default:o((()=>[c(E,null,{default:o((()=>[u("优投描述")])),_:1}),c(B,{class:"vip-merchandiszis"},{default:o((()=>[u(" “一键优投”资金不受合伙人制度限制，且佣金更低，统一性强，便于操盘，分析师会在机构通道优先进场。优投周期内无需任何操作，避免忘记优投，每日本金和盈利自动跟随分析师策略自动交易，解放双手实现高额收益。 ")])),_:1})])),_:1}),c(B,{class:"content-area"},{default:o((()=>[1==V.switc.trader_record?(d(),i(B,{key:0,class:"follow-description"},{default:o((()=>[c(B,{class:"base-title"},{default:o((()=>[u("导师战绩")])),_:1})])),_:1})):f("",!0),1==V.switc.trader_record?(d(),i(B,{key:1,class:"record"},{default:o((()=>[c(B,{class:"mun"},{default:o((()=>[c(B,{class:"mun-tse"},{default:o((()=>[u("总收益")])),_:1}),c(B,{class:"mun-bse"},{default:o((()=>[u(r(V.investorDetail.total_revenue)+"%",1)])),_:1})])),_:1}),c(B,{class:"mun-se"},{default:o((()=>[c(B,{class:"mun-selse"},{default:o((()=>[c(B,{class:"mun-zis"},{default:o((()=>[u("月收益")])),_:1}),c(B,{class:"mun-zis"},{default:o((()=>[u(r(V.investorDetail.monthly_revenue)+"%",1)])),_:1})])),_:1}),c(B,{class:"mun-selse"},{default:o((()=>[c(B,{class:"mun-zis"},{default:o((()=>[u("仓位")])),_:1}),c(B,{class:"mun-zis"},{default:o((()=>[u(r(V.investorDetail.position)+"%",1)])),_:1})])),_:1}),c(B,{class:"mun-selse"},{default:o((()=>[c(B,{class:"mun-zis"},{default:o((()=>[u("最大回撤")])),_:1}),c(B,{class:"mun-zis"},{default:o((()=>[u(r(V.investorDetail.maximum_rollback)+"%",1)])),_:1})])),_:1}),c(B,{class:"mun-selse"},{default:o((()=>[c(B,{class:"mun-zis"},{default:o((()=>[u("胜率")])),_:1}),c(B,{class:"mun-zis"},{default:o((()=>[u(r(V.investorDetail.win_obbs)+"%",1)])),_:1})])),_:1})])),_:1})])),_:1})):f("",!0),1==V.switc.trader_detailed?(d(),i(B,{key:2,class:"follow-description"},{default:o((()=>[c(B,{class:"todn"}),c(B,{class:"base-title"},{default:o((()=>[u("持仓明细")])),_:1})])),_:1})):f("",!0),1==V.switc.trader_detailed?(d(),i(B,{key:3,class:"table"},{default:o((()=>[c(B,{class:"table-head"},{default:o((()=>[c(E,null,{default:o((()=>[u("股票名称")])),_:1}),c(E,null,{default:o((()=>[u("成本价")])),_:1}),c(E,null,{default:o((()=>[u("浮动收益")])),_:1}),c(E,null,{default:o((()=>[u("买入/卖出")])),_:1})])),_:1}),V.investorlist.length?(d(),i(B,{key:0,class:"table-row-wrapper"},{default:o((()=>[(d(!0),m(h,null,p(V.investorlist,((e,l)=>(d(),i(B,{class:"table-row",key:l},{default:o((()=>[c(B,{class:"table-row-span"},{default:o((()=>[u(r(e.stock_name),1)])),_:2},1024),c(B,{class:"table-row-span"},{default:o((()=>[u(r(e.buy_price),1)])),_:2},1024),c(B,{class:k(["table-row-span",A.getEarningsClass(e.floating_ratio)])},{default:o((()=>[u(r(e.floating_ratio),1)])),_:2},1032,["class"]),c(B,{class:"table-row-span"},{default:o((()=>[c(E,null,{default:o((()=>[u(r(e.buy_time),1)])),_:2},1024),c(E,null,{default:o((()=>[u(r(e.sell_time||"--"),1)])),_:2},1024)])),_:2},1024)])),_:2},1024)))),128))])),_:1})):(d(),i(G,{key:1,mode:"data",icon:"/static/empty/empty-data.png"}))])),_:1})):f("",!0),c(B,{class:"follow-description"},{default:o((()=>[c(B,{class:"todn"}),c(B,{class:"base-title"},{default:o((()=>[u("VIP说明")])),_:1})])),_:1}),c(B,{class:"table table1"},{default:o((()=>[c(B,{class:"table-head"},{default:o((()=>[c(E,{class:"capital-threshold"},{default:o((()=>[u("资金门槛")])),_:1}),c(E,null,{default:o((()=>[u("投资区间")])),_:1}),c(E,null,{default:o((()=>[u("交易周期")])),_:1}),c(E,null,{default:o((()=>[u("盈利佣金")])),_:1})])),_:1}),c(B,{class:"table-row-wrapper"},{default:o((()=>[(d(!0),m(h,null,p(V.infoList,((e,l)=>(d(),i(B,{class:"table-row",key:l},{default:o((()=>[c(B,{class:"table-row-span capital-threshold"},{default:o((()=>[(d(!0),m(h,null,p(e.user_level_list,((e,l)=>(d(),i(B,{key:l},{default:o((()=>[u(r(e),1)])),_:2},1024)))),128))])),_:2},1024),c(B,{class:"table-row-span"},{default:o((()=>[u(r(e.min_money_text)+"~"+r(e.max_money_text),1)])),_:2},1024),c(B,{class:"table-row-span"},{default:o((()=>[u(r(e.cycle_text),1)])),_:2},1024),c(B,{class:"table-row-span"},{default:o((()=>[u(r(e.commission)+"%",1)])),_:2},1024)])),_:2},1024)))),128))])),_:1})])),_:1})])),_:1})])),_:1})}],["__scopeId","data-v-3dfddef4"]]);export{E as default};
