import{aq as e,Z as l,r as t,q as s,x as a,y as i,z as o,A as n,C as c,H as u,I as d,J as r,G as f,K as _,D as m,E as p,F as h,N as b,O as x,b7 as y,aa as v,L as g,B as k,M as w}from"./index-DgmPZ0G5.js";import{_ as C}from"./u-navbar.CFdDbSNT.js";import{r as D}from"./uni-app.es.DuVg4RGY.js";import{_ as L}from"./u-image.DByvv1Y3.js";import{_ as z,a as I}from"./u-checkbox-group.DHfbSoI_.js";import{_ as j}from"./u-empty.B_4P7i6r.js";import{v as V,a as A,x as T,y as B}from"./index.DJMEk9DT.js";import{_ as N}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./u-status-bar.I29E-D2N.js";import"./u-icon.CMhWs0pO.js";import"./u-transition.DV0Ma9jh.js";const O=N({data:()=>({switc:"",radiolist1:[{name:"",disabled:!1}],checkboxValue1:0,radiovalue1:"",investorDetail:"",investorlist:[],select:null,documentaryAmount:"",infoList:[],id:0,subInfoList:[],titles:"",lastClickTime:0,interval:3e3}),methods:{cuti(){e("/pages/trading-record/trading-record",{id:this.id})},back:l,service(){t({url:"/pages/service-agreement/service-agreement"})},checkboxChange(e){1==e[0]?this.checkboxValue1=e[0]:this.checkboxValue1=0},onButtonClick(){V({money:this.documentaryAmount,cycle:this.subInfoList[this.select].cycle,trader_id:this.investorDetail.id,order_type:3,fund_id:this.investorDetail.fund_id,divide_into:this.subInfoList[this.select].commission}).then((l=>{"买入成功"==l.message?(setTimeout((()=>{e("/pages/record/record",{id:20})}),1e3),s({title:l.message,icon:"none",duration:1e3})):s({title:l.message,icon:"none",duration:2e3})}))},documen(){if(0!=this.checkboxValue1)if(null!=this.select)if(""!=this.documentaryAmount){if(function(e){return e%100==0}(this.documentaryAmount)){const e=Date.now();e-this.lastClickTime>this.interval&&(this.lastClickTime=e,this.onButtonClick())}else s({title:"优投金额必须是100的整数倍",icon:"none",duration:2e3})}else s({title:"请输入优投金额",icon:"none",duration:2e3});else s({title:"请选择交易周期",icon:"none",duration:2e3});else s({title:"请阅读并同意《服务协议》",icon:"none",duration:2e3})},rightClick(){e("/pages/record/record",{id:20})},sele(e){this.select=e,this.infoList=this.subInfoList.filter(((l,t)=>t==e))},getEarningsClass(e){const l=e.split("%");return l.length>1&&l[0]<0?"down-color":""}},onLoad(e){A().then((e=>{this.switc=e.data.module})),this.id=e.id,this.titles=e.title,T({id:e.id}).then((e=>{this.investorDetail=e.data.form,this.investorlist=e.data.list})),B({traderid:e.id}).then((e=>{this.infoList=e.data.list,this.subInfoList=JSON.parse(JSON.stringify(e.data.list)),this.sele(0)}))}},[["render",function(e,l,t,s,V,A){const T=c,B=D(a("up-navbar"),C),N=b,O=x,S=y,q=D(a("up-image"),L),E=D(a("up-checkbox"),z),J=D(a("up-checkbox-group"),I),P=D(a("up-empty"),j);return i(),o(T,null,{default:n((()=>[u(B,{title:V.titles,onRightClick:A.rightClick,bgColor:"#f6f6f6","left-icon":"arrow-left",autoBack:!1,rightText:"优投记录",placeholder:!0,onLeftClick:A.back},{right:n((()=>[u(T,{style:{color:"#CD8026","font-size":"26rpx"}},{default:n((()=>[d("优投记录")])),_:1})])),_:1},8,["title","onRightClick","onLeftClick"]),u(T,{class:"follow-detail-card-box"},{default:n((()=>[u(T,{class:"follow-detail-card"},{default:n((()=>[u(T,{class:"header-box"},{default:n((()=>[u(T,null,{default:n((()=>[d("明星导师 | VIP")])),_:1})])),_:1}),u(T,{style:{"background-color":"#fff","border-radius":"12rpx",border:"1px solid #e9e9e9","margin-top":"-50rpx",overflow:"hidden"}},{default:n((()=>[u(T,{class:"box1"},{default:n((()=>[u(N,{src:V.investorDetail.head_img,mode:""},null,8,["src"]),u(T,{class:"follow-detail-info"},{default:n((()=>[u(T,{class:"follow-detail-title"},{default:n((()=>[u(T,{class:"title"},{default:n((()=>[d(r(V.investorDetail.name),1)])),_:1}),u(T,{class:"follow-detail-tags"},{default:n((()=>[V.investorDetail.level_name?(i(),o(O,{key:0},{default:n((()=>[d(r(V.investorDetail.level_name),1)])),_:1})):f("",!0),V.investorDetail.experience?(i(),o(O,{key:1},{default:n((()=>[d(r(V.investorDetail.experience),1)])),_:1})):f("",!0)])),_:1})])),_:1}),u(T,{style:{"font-weight":"400","font-size":"23rpx",color:"#979797"}},{default:n((()=>[d(r(V.investorDetail.explain),1)])),_:1})])),_:1})])),_:1}),u(T,{class:"box2"},{default:n((()=>[u(T,null,{default:n((()=>[u(O,null,{default:n((()=>[d("最低跟额："+r(V.select||0==V.select?V.subInfoList[V.select].min_money_text:V.investorDetail.min_money),1)])),_:1}),u(O,null,{default:n((()=>[d("导师抽佣："+r(V.select||0==V.select?V.subInfoList[V.select].commission:"0")+"%",1)])),_:1}),u(O,null,{default:n((()=>[d("最高跟额："+r(V.select||0==V.select?V.subInfoList[V.select].max_money_text:V.investorDetail.max_money),1)])),_:1})])),_:1})])),_:1})])),_:1})])),_:1})])),_:1}),u(T,{class:"follow-price"},{default:n((()=>[u(T,{class:"follow-price-top"},{default:n((()=>[u(T,{class:"mudsf"},{default:n((()=>[d(" 最低优投金额 ")])),_:1}),_("span",null,"（必须是100的倍数）")])),_:1}),u(T,{style:{width:"100%",padding:"0 20rpx","box-sizing":"border-box"}},{default:n((()=>[u(S,{type:"number",placeholder:"请输入优投金额",modelValue:V.documentaryAmount,"onUpdate:modelValue":l[0]||(l[0]=e=>V.documentaryAmount=e)},null,8,["modelValue"])])),_:1})])),_:1}),u(T,{class:"vip-follow-cards"},{default:n((()=>[(i(!0),m(h,null,p(V.subInfoList,((e,l)=>(i(),o(T,{class:"vip-follow-card",key:l,onClick:e=>A.sele(l),style:v(V.select==l?"border: 1px solid #e35540;":"")},{default:n((()=>[u(T,{class:"base-card"},{default:n((()=>[u(T,{class:"flex-center"},{default:n((()=>[g(u(T,{class:k(["points",(V.select,"on")])},{default:n((()=>["普通会员"!=e.user_level?(i(),o(q,{key:0,src:"/static/images/edit12.png",width:"25rpx",height:"30rpx"})):f("",!0)])),_:2},1032,["class"]),[[w,1==e.user_level_require]]),u(T,{class:"flex-cenwf"},{default:n((()=>[d(r(e.cycle)+" 个交易日 ",1)])),_:2},1024)])),_:2},1024),u(T,{class:"vip-merchandiszis"},{default:n((()=>[d(r(e.min_money_text)+"~"+r(e.max_money_text),1)])),_:2},1024)])),_:2},1024)])),_:2},1032,["onClick","style"])))),128))])),_:1}),u(T,{class:"flsex"},{default:n((()=>[u(J,{placement:"column",onChange:A.checkboxChange},{default:n((()=>[(i(!0),m(h,null,p(V.radiolist1,((e,l)=>(i(),o(E,{customStyle:{marginBottom:"8px"},key:l,label:e.name,name:"1"},null,8,["label"])))),128))])),_:1},8,["onChange"]),u(T,{class:"flsex_lse"},{default:n((()=>[d("已阅读并同意")])),_:1}),u(T,{class:"flsex_rse",onClick:l[1]||(l[1]=e=>A.service())},{default:n((()=>[d("《服务协议》")])),_:1})])),_:1}),u(T,{class:"flex-cdafr",onClick:A.documen},{default:n((()=>[d(" 一键优投 ")])),_:1},8,["onClick"]),u(T,{class:"vip-follow-desc"},{default:n((()=>[u(O,null,{default:n((()=>[d("优投描述")])),_:1}),u(T,{class:"vip-merchandiszis"},{default:n((()=>[d(" “一键优投”资金不受合伙人制度限制，且佣金更低，统一性强，便于操盘，分析师会在机构通道优先进场。优投周期内无需任何操作，避免忘记优投，每日本金和盈利自动跟随分析师策略自动交易，解放双手实现高额收益。 ")])),_:1})])),_:1}),u(T,{class:"content-area"},{default:n((()=>[1==V.switc.trader_record?(i(),o(T,{key:0,class:"follow-description"},{default:n((()=>[u(T,{class:"base-title"},{default:n((()=>[d("导师战绩")])),_:1})])),_:1})):f("",!0),1==V.switc.trader_record?(i(),o(T,{key:1,class:"record"},{default:n((()=>[u(T,{class:"mun"},{default:n((()=>[u(T,{class:"mun-tse"},{default:n((()=>[d("总收益")])),_:1}),u(T,{class:"mun-bse"},{default:n((()=>[d(r(V.investorDetail.total_revenue)+"%",1)])),_:1})])),_:1}),u(T,{class:"mun-se"},{default:n((()=>[u(T,{class:"mun-selse"},{default:n((()=>[u(T,{class:"mun-zis"},{default:n((()=>[d("月收益")])),_:1}),u(T,{class:"mun-zis"},{default:n((()=>[d(r(V.investorDetail.monthly_revenue)+"%",1)])),_:1})])),_:1}),u(T,{class:"mun-selse"},{default:n((()=>[u(T,{class:"mun-zis"},{default:n((()=>[d("仓位")])),_:1}),u(T,{class:"mun-zis"},{default:n((()=>[d(r(V.investorDetail.position)+"%",1)])),_:1})])),_:1}),u(T,{class:"mun-selse"},{default:n((()=>[u(T,{class:"mun-zis"},{default:n((()=>[d("最大回撤")])),_:1}),u(T,{class:"mun-zis"},{default:n((()=>[d(r(V.investorDetail.maximum_rollback)+"%",1)])),_:1})])),_:1}),u(T,{class:"mun-selse"},{default:n((()=>[u(T,{class:"mun-zis"},{default:n((()=>[d("胜率")])),_:1}),u(T,{class:"mun-zis"},{default:n((()=>[d(r(V.investorDetail.win_obbs)+"%",1)])),_:1})])),_:1})])),_:1})])),_:1})):f("",!0),1==V.switc.trader_detailed?(i(),o(T,{key:2,class:"follow-description"},{default:n((()=>[u(T,{class:"todn"}),u(T,{class:"base-title"},{default:n((()=>[d("持仓明细")])),_:1})])),_:1})):f("",!0),1==V.switc.trader_detailed?(i(),o(T,{key:3,class:"table"},{default:n((()=>[u(T,{class:"table-head"},{default:n((()=>[u(O,null,{default:n((()=>[d("当前持仓")])),_:1}),u(O,null,{default:n((()=>[d("成本价")])),_:1}),u(O,null,{default:n((()=>[d("浮动收益")])),_:1}),u(O,null,{default:n((()=>[d("买入/卖出")])),_:1})])),_:1}),V.investorlist.length?(i(),o(T,{key:0,class:"table-row-wrapper"},{default:n((()=>[(i(!0),m(h,null,p(V.investorlist,((e,l)=>(i(),o(T,{class:"table-row",key:l},{default:n((()=>[u(T,{class:"table-row-span"},{default:n((()=>[d(r(e.stock_name),1)])),_:2},1024),u(T,{class:"table-row-span"},{default:n((()=>[d(r(e.buy_price),1)])),_:2},1024),u(T,{class:k(["table-row-span",A.getEarningsClass(e.floating_ratio)])},{default:n((()=>[d(r(e.floating_ratio),1)])),_:2},1032,["class"]),u(T,{class:"table-row-span"},{default:n((()=>[u(O,null,{default:n((()=>[d(r(e.buy_time),1)])),_:2},1024),u(O,null,{default:n((()=>[d(r(e.sell_time||"--"),1)])),_:2},1024)])),_:2},1024)])),_:2},1024)))),128))])),_:1})):(i(),o(P,{key:1,mode:"data",icon:"/static/empty/empty-data.png"}))])),_:1})):f("",!0),u(T,{class:"follow-description"},{default:n((()=>[u(T,{class:"todn"}),u(T,{class:"base-title"},{default:n((()=>[d("VIP说明")])),_:1})])),_:1}),u(T,{class:"table table1"},{default:n((()=>[u(T,{class:"table-head"},{default:n((()=>[u(O,{class:"capital-threshold"},{default:n((()=>[d("资金门槛")])),_:1}),u(O,null,{default:n((()=>[d("投资区间")])),_:1}),u(O,null,{default:n((()=>[d("交易周期")])),_:1}),u(O,null,{default:n((()=>[d("盈利佣金")])),_:1})])),_:1}),u(T,{class:"table-row-wrapper"},{default:n((()=>[(i(!0),m(h,null,p(V.infoList,((e,l)=>(i(),o(T,{class:"table-row",key:l},{default:n((()=>[u(T,{class:"table-row-span capital-threshold"},{default:n((()=>[(i(!0),m(h,null,p(e.user_level_list,((e,l)=>(i(),o(T,{key:l},{default:n((()=>[d(r(e),1)])),_:2},1024)))),128))])),_:2},1024),u(T,{class:"table-row-span"},{default:n((()=>[d(r(e.min_money_text)+"~"+r(e.max_money_text),1)])),_:2},1024),u(T,{class:"table-row-span"},{default:n((()=>[d(r(e.cycle_text),1)])),_:2},1024),u(T,{class:"table-row-span"},{default:n((()=>[d(r(e.commission)+"%",1)])),_:2},1024)])),_:2},1024)))),128))])),_:1})])),_:1})])),_:1})])),_:1})}],["__scopeId","data-v-407252bc"]]);export{O as default};
