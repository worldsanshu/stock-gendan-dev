import{R as t,V as e,ap as a,ai as l,x as s,y as i,D as o,H as n,Y as r,A as u,F as c,aq as d,C as m,Z as p,E as f,z as _,G as g,I as x,J as v,N as h}from"./index-DQ1rtinv.js";import{_ as y}from"./uni-nav-bar.DjBb34GI.js";import{o as k,r as b}from"./uni-app.es.Cjy5VdAU.js";import{_ as j}from"./u-tabs.Cw-8ye8Y.js";import{_ as D}from"./u-sticky.DUtNFL8D.js";import{_ as w}from"./u-image.DuKB8fGP.js";import{_ as $}from"./u-empty.fZOHUUkH.js";import{_ as C}from"./u-loadmore.BJKnQKKx.js";import{c as F,d as I}from"./index.CRxDyK41.js";import{_ as T}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.CVC5mL0W.js";import"./uni-status-bar.CY3EcjAi.js";import"./u-icon.CGC9KXiC.js";import"./u-transition.ZwOiaRHn.js";import"./u-line.B0mzGBy-.js";import"./u-loading-icon.CWztmby-.js";const z=""+new URL("lineChartImg@2x-BX72JeLJ.png",import.meta.url).href,L=T({__name:"copy-trading",setup(T){const{t:L}=t(),M=e(1),N=e(1),R=e(),V=e(1),G=e("loadmore"),J=e([]),S=e([{name:L("stockDetail.text.collect"),cate_id:""},{name:L("stockDetail.text.all"),cate_id:""},{name:L("stockDetail.text.spotGrid"),cate_id:1},{name:L("stockDetail.text.contractGrid"),cate_id:2},{name:L("stockDetail.text.contractMartingale"),cate_id:4},{name:L("stockDetail.text.gem"),cate_id:6}]);function A(){d("/pages/my-investment/my-investment",{})}const O=()=>{N.value>=V.value&&(G.value="nomore"),N.value>=V.value||(G.value="loading",N.value++,setTimeout((()=>{W()}),0))};function U(t){console.log("item",t),R.value=t.cate_id,N.value=1,0==t.index?I({page:N.value}).then((t=>{J.value=t.data.list.data})):F({page:N.value,cate_id:t.cate_id}).then((t=>{J.value=t.data.list.data}))}const W=()=>{F({page:N.value,cate_id:R.value}).then((t=>{for(let e=0;e<t.data.list.data.length;e++)J.value.push(t.data.list.data[e]);V.value=t.data.last_page,0==t.data.list.data.length&&(G.value="nomore")}))};return a((()=>{W()})),k((()=>{J.value=[],N.value=1,W(),setTimeout((function(){l()}),600)})),(t,e)=>{const a=b(s("uni-nav-bar"),y),l=b(s("up-tabs"),j),k=m,F=b(s("up-sticky"),D),I=b(s("up-image"),w),T=h,L=b(s("up-empty"),$),N=b(s("up-loadmore"),C);return i(),o(c,null,[n(a,{title:t.$t("page.title.strategyTrading"),"status-bar":"","background-color":"#ff3c36",color:"#fff","left-icon":"left","right-text":t.$t("stockDetail.text.myInvestment"),fixed:"",border:!1,onClickLeft:r(p),onClickRight:A},null,8,["title","right-text","onClickLeft"]),n(k,{class:"copy-trading-page"},{default:u((()=>[n(F,{style:{top:"0","z-index":"22"},"bg-color":"#fff"},{default:u((()=>[n(k,{class:"tab"},{default:u((()=>[n(l,{list:S.value,onClick:U,current:M.value,lineColor:"#FD4630",lineWidth:"30",inactiveStyle:{color:"#979797","font-size":"27rpx","font-weight":"400"},activeStyle:{color:"#FD4630","font-weight":"500","font-size":"31rpx"}},null,8,["list","current"])])),_:1})])),_:1}),n(k,{class:"trading-item-box"},{default:u((()=>[(i(!0),o(c,null,f(J.value,(e=>(i(),_(k,{class:"trading-item",key:e.id,onClick:t=>{return a=e,console.log("110",a.name),void d("/pages/content/content",{title:a.name,id:a.id});var a}},{default:u((()=>[n(k,{class:"fatkun-drop-panel"},{default:u((()=>[n(k,{class:"item-header"},{default:u((()=>[n(k,{class:"header-left"},{default:u((()=>[n(k,{class:"header-title"},{default:u((()=>[x(v(e.name),1)])),_:2},1024),n(k,{class:"header-tabs"},{default:u((()=>[n(k,{class:"header-tab-item"},{default:u((()=>[n(I,{src:"/static/images/edit11.png",mode:"widthFix",width:"26rpx",height:"23rpx"}),n(k,{class:"mundi-p5"},{default:u((()=>[x(v(e.cate_text),1)])),_:2},1024)])),_:2},1024),n(k,{class:"header-tab-item-gray"},{default:u((()=>[n(k,{class:"topk"},{default:u((()=>[x(v(e.how),1)])),_:2},1024)])),_:2},1024),n(k,{class:"header-tab-item-gray"},{default:u((()=>[n(k,{class:"topk"},{default:u((()=>[x(v(t.$t("stockDetail.text.minimumInvestmentAmount"))+"¥"+v(e.min_invest_money),1)])),_:2},1024)])),_:2},1024)])),_:2},1024)])),_:2},1024),n(k,{class:"list-item-right"},{default:u((()=>[n(I,{src:"/static/Vertretung/peopleNumber@2x.png",mode:"widthFix",width:"26rpx",height:"24rpx",style:{opacity:"0.6"}}),n(k,{class:"tomia-to6"},{default:u((()=>[x(v(e.use_num),1)])),_:2},1024)])),_:2},1024)])),_:2},1024),n(k,{class:"yield-data"},{default:u((()=>[n(k,{class:"ydj-i5"},{default:u((()=>[x(v(0!=e.income?"+"+e.income+".00":e.income+".00")+"%",1)])),_:2},1024),n(T,{src:z,mode:""})])),_:2},1024),n(k,{class:"yield"},{default:u((()=>[x(v(t.$t("stockDetail.text.rateOfReturn")),1)])),_:1}),n(k,{style:{display:"flex","align-items":"center","justify-content":"space-between",position:"relative"}},{default:u((()=>[n(k,{style:{display:"flex","align-items":"center","justify-content":"space-between"}},{default:u((()=>[n(k,{class:"trading-list"},{default:u((()=>[n(k,{class:"munow"},{default:u((()=>[x(v(t.$t("stockDetail.text.runTime")),1)])),_:1}),n(k,{class:"topi-v6"},{default:u((()=>[x(v(e.run_time_text.day)+v(t.$t("common.text.day2"))+v(e.run_time_text.hour)+"时"+v(e.run_time_text.min)+v(t.$t("common.text.minute")),1)])),_:2},1024)])),_:2},1024),n(k,{class:"list-item"},{default:u((()=>[n(k,{class:"list-item-left"},{default:u((()=>[n(k,{class:"munow"},{default:u((()=>[x(v(t.$t("stockDetail.text.redemptionNumber")),1)])),_:1}),n(k,{class:"topi-v6"},{default:u((()=>[x(v(e.max_retreat),1)])),_:2},1024)])),_:2},1024)])),_:2},1024)])),_:2},1024),n(k,{class:"mudsc"},{default:u((()=>[x(v(t.$t("stockDetail.text.buy")),1)])),_:1})])),_:2},1024)])),_:2},1024)])),_:2},1032,["onClick"])))),128))])),_:1}),0==J.value.length?(i(),_(L,{key:0,mode:"data",width:"200","margin-top":"520rpx",icon:"/static/Vertretung/noMoreData@2x.png"})):g("",!0),J.value.length>0?(i(),_(N,{key:1,status:G.value,onClick:O,"loading-text":t.$t("common.text.loading"),"loadmore-text":t.$t("common.text.loadMore"),"nomore-text":t.$t("common.text.noMore")},null,8,["status","loading-text","loadmore-text","nomore-text"])):g("",!0)])),_:1})],64)}}},[["__scopeId","data-v-6e683859"]]);export{L as default};
