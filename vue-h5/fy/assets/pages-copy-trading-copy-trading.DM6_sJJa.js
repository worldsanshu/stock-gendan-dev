import{R as t,V as e,ap as a,aj as l,x as s,D as i,H as o,Y as n,z as r,F as u,aq as c,B as d,C as m,Z as p,E as f,y as _,G as g,I as x,J as v,N as h}from"./index-V1zTiwCe.js";import{_ as y}from"./uni-nav-bar.B9quvnCD.js";import{c as k,r as b}from"./uni-app.es.CQrvLY-P.js";import{_ as j}from"./u-tabs.Bpv7OLDV.js";import{_ as D}from"./u-sticky.B_Rj7F4w.js";import{_ as w}from"./u-image.DpaUX6D5.js";import{_ as $}from"./u-empty.CAIIj3fX.js";import{_ as C}from"./u-loadmore.Bh-x72ej.js";import{c as I,d as F}from"./index.C621UsXl.js";import{_ as R}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.DZWFI7QG.js";import"./uni-status-bar.BjedvyN3.js";import"./u-icon.EexHLkhm.js";import"./u-transition.BCj5qmVK.js";import"./u-line.BcX0DQci.js";import"./u-loading-icon.COOKVkTA.js";const z=""+new URL("lineChartImg@2x-BX72JeLJ.png",import.meta.url).href,J=R({__name:"copy-trading",setup(R){const{t:J}=t(),L=e(1),M=e(1),T=e(),V=e(1),B=e("loadmore"),G=e([]),N=e([{name:J("stockDetail.text.collect"),cate_id:""},{name:J("stockDetail.text.all"),cate_id:""},{name:J("stockDetail.text.spotGrid"),cate_id:1},{name:J("stockDetail.text.contractGrid"),cate_id:2},{name:J("stockDetail.text.contractMartingale"),cate_id:4},{name:J("stockDetail.text.gem"),cate_id:6}]);function A(){c("/pages/my-investment/my-investment",{})}const E=()=>{M.value>=V.value&&(B.value="nomore"),M.value>=V.value||(B.value="loading",M.value++,setTimeout((()=>{S()}),0))};function H(t){console.log("item",t),T.value=t.cate_id,M.value=1,0==t.index?F({page:M.value}).then((t=>{G.value=t.data.list.data})):I({page:M.value,cate_id:t.cate_id}).then((t=>{G.value=t.data.list.data}))}const S=()=>{I({page:M.value,cate_id:T.value}).then((t=>{for(let e=0;e<t.data.list.data.length;e++)G.value.push(t.data.list.data[e]);V.value=t.data.last_page,0==t.data.list.data.length&&(B.value="nomore")}))};return a((()=>{S()})),k((()=>{G.value=[],M.value=1,S(),setTimeout((function(){l()}),600)})),(t,e)=>{const a=b(s("uni-nav-bar"),y),l=b(s("up-tabs"),j),k=d,I=b(s("up-sticky"),D),F=b(s("up-image"),w),R=h,J=b(s("up-empty"),$),M=b(s("up-loadmore"),C);return m(),i(u,null,[o(a,{title:t.$t("page.title.strategyTrading"),"status-bar":"","background-color":"#ff3c36",color:"#fff","left-icon":"left","right-text":t.$t("stockDetail.text.myInvestment"),fixed:"",border:!1,onClickLeft:n(p),onClickRight:A},null,8,["title","right-text","onClickLeft"]),o(k,{class:"copy-trading-page"},{default:r((()=>[o(I,{style:{top:"0","z-index":"22"},"bg-color":"#fff"},{default:r((()=>[o(k,{class:"tab"},{default:r((()=>[o(l,{list:N.value,onClick:H,current:L.value,lineColor:"#FD4630",lineWidth:"30",inactiveStyle:{color:"#979797","font-size":"27rpx","font-weight":"400"},activeStyle:{color:"#FD4630","font-weight":"500","font-size":"31rpx"}},null,8,["list","current"])])),_:1})])),_:1}),o(k,{class:"trading-item-box"},{default:r((()=>[(m(!0),i(u,null,f(G.value,(e=>(m(),_(k,{class:"trading-item",key:e.id,onClick:t=>{return a=e,console.log("110",a.name),void c("/pages/content/content",{title:a.name,id:a.id});var a}},{default:r((()=>[o(k,{class:"fatkun-drop-panel"},{default:r((()=>[o(k,{class:"item-header"},{default:r((()=>[o(k,{class:"header-left"},{default:r((()=>[o(k,{class:"header-title"},{default:r((()=>[x(v(e.name),1)])),_:2},1024),o(k,{class:"header-tabs"},{default:r((()=>[o(k,{class:"header-tab-item"},{default:r((()=>[o(F,{src:"/static/images/edit11.png",mode:"widthFix",width:"26rpx",height:"23rpx"}),o(k,{class:"mundi-p5"},{default:r((()=>[x(v(e.cate_text),1)])),_:2},1024)])),_:2},1024),o(k,{class:"header-tab-item-gray"},{default:r((()=>[o(k,{class:"topk"},{default:r((()=>[x(v(e.how),1)])),_:2},1024)])),_:2},1024),o(k,{class:"header-tab-item-gray"},{default:r((()=>[o(k,{class:"topk"},{default:r((()=>[x(v(t.$t("stockDetail.text.minimumInvestmentAmount"))+"¥"+v(e.min_invest_money),1)])),_:2},1024)])),_:2},1024)])),_:2},1024)])),_:2},1024),o(k,{class:"list-item-right"},{default:r((()=>[o(F,{src:"/static/Vertretung/peopleNumber@2x.png",mode:"widthFix",width:"26rpx",height:"24rpx",style:{opacity:"0.6"}}),o(k,{class:"tomia-to6"},{default:r((()=>[x(v(e.use_num),1)])),_:2},1024)])),_:2},1024)])),_:2},1024),o(k,{class:"yield-data"},{default:r((()=>[o(k,{class:"ydj-i5"},{default:r((()=>[x(v(0!=e.income?"+"+e.income+".00":e.income+".00")+"%",1)])),_:2},1024),o(R,{src:z,mode:""})])),_:2},1024),o(k,{class:"yield"},{default:r((()=>[x(v(t.$t("stockDetail.text.rateOfReturn")),1)])),_:1}),o(k,{style:{display:"flex","align-items":"center","justify-content":"space-between",position:"relative"}},{default:r((()=>[o(k,{style:{display:"flex","align-items":"center","justify-content":"space-between"}},{default:r((()=>[o(k,{class:"trading-list"},{default:r((()=>[o(k,{class:"munow"},{default:r((()=>[x(v(t.$t("stockDetail.text.runTime")),1)])),_:1}),o(k,{class:"topi-v6"},{default:r((()=>[x(v(e.run_time_text.day)+v(t.$t("common.text.day2"))+v(e.run_time_text.hour)+"时"+v(e.run_time_text.min)+v(t.$t("common.text.minute")),1)])),_:2},1024)])),_:2},1024),o(k,{class:"list-item"},{default:r((()=>[o(k,{class:"list-item-left"},{default:r((()=>[o(k,{class:"munow"},{default:r((()=>[x(v(t.$t("stockDetail.text.redemptionNumber")),1)])),_:1}),o(k,{class:"topi-v6"},{default:r((()=>[x(v(e.max_retreat),1)])),_:2},1024)])),_:2},1024)])),_:2},1024)])),_:2},1024),o(k,{class:"mudsc"},{default:r((()=>[x(v(t.$t("stockDetail.text.buy")),1)])),_:1})])),_:2},1024)])),_:2},1024)])),_:2},1032,["onClick"])))),128))])),_:1}),0==G.value.length?(m(),_(J,{key:0,mode:"data",width:"200","margin-top":"520rpx",icon:"/static/Vertretung/noMoreData@2x.png"})):g("",!0),G.value.length>0?(m(),_(M,{key:1,status:B.value,onClick:E,"loading-text":t.$t("common.text.loading"),"loadmore-text":t.$t("common.text.loadMore"),"nomore-text":t.$t("common.text.noMore")},null,8,["status","loading-text","loadmore-text","nomore-text"])):g("",!0)])),_:1})],64)}}},[["__scopeId","data-v-6e683859"]]);export{J as default};