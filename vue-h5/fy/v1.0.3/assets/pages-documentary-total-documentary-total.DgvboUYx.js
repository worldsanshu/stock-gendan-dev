import{ai as t,x as e,y as a,z as s,A as o,C as r,D as l,E as m,F as i,G as n,H as d,I as u,J as c}from"./index-BSZMSohE.js";import{_ as p}from"./u-loadmore.9Ovjbuhv.js";import{r as _}from"./uni-app.es.xTxZrAO-.js";import{_ as g}from"./u-empty.lkjQl3ZA.js";import{o as h}from"./index.D3LolpWH.js";import{_ as f}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./u-line.Bg8FUxRG.js";import"./u-loading-icon.oQN9K4Zk.js";import"./u-icon.rSo6jUIp.js";const x=f({data:()=>({page:1,recordList:[],per_page:1,status:"loadmore"}),methods:{onReachBottom(){this.page>=this.per_page&&(this.status="nomore"),this.page>=this.per_page||(this.status="loading",this.page++,setTimeout((()=>{this.getInve()}),0))},getInve(){h({page:this.page}).then((t=>{for(let e=0;e<t.data.list.data.length;e++)this.recordList.push(t.data.list.data[e]);this.per_page=t.data.list.last_page}))}},mounted(){this.getInve()},onPullDownRefresh(){this.recordList=[],this.page=1,this.getInve(),setTimeout((function(){t()}),1e3)}},[["render",function(t,h,f,x,y,$){const j=r,v=_(e("up-loadmore"),p),I=_(e("up-empty"),g);return a(),s(j,null,{default:o((()=>[(a(!0),l(i,null,m(y.recordList,((e,r)=>(a(),s(j,{class:"page_list_item",key:r},{default:o((()=>[d(j,{style:{height:"30rpx"}}),d(j,{class:"list_header"},{default:o((()=>[d(j,{class:"munds1"},{default:o((()=>[d(j,{class:"mr-5"},{default:o((()=>[u(c(e.name),1)])),_:2},1024),d(j,{class:"mr-5"},{default:o((()=>[u(c(2==e.order_type?`(${t.$t("common.text.dailySmartInvestment")})`:`(${t.$t("common.text.oneClickSmartInvestment")})`),1)])),_:2},1024)])),_:2},1024),d(j,{class:"munds2"},{default:o((()=>[d(j,{class:"mr-5"},{default:o((()=>[u(c(t.$t("common.text.orderAmount")),1)])),_:1})])),_:1})])),_:2},1024),d(j,{class:"list_middle"},{default:o((()=>[d(j,{class:"page_head"},{default:o((()=>[d(j,{class:"mr-5"},{default:o((()=>[u(c(t.$t("common.text.orderNumber"))+c(e.order_sn),1)])),_:2},1024)])),_:2},1024),d(j,{class:"yui-la"},{default:o((()=>[d(j,{class:"mr-5"},{default:o((()=>[u("￥"+c(e.money),1)])),_:2},1024)])),_:2},1024)])),_:2},1024),d(j,{class:"list_time"},{default:o((()=>[d(j,{class:"mr-6"},{default:o((()=>[u(c(e.create_time_text),1)])),_:2},1024)])),_:2},1024)])),_:2},1024)))),128)),y.recordList.length>0?(a(),s(v,{key:0,status:y.status,onClick:$.onReachBottom,"loading-text":t.$t("common.text.loading"),"loadmore-text":t.$t("common.text.loadMore"),"nomore-text":t.$t("common.text.noMore")},null,8,["status","onClick","loading-text","loadmore-text","nomore-text"])):n("",!0),0==y.recordList.length?(a(),s(I,{key:1,mode:"data",width:"200","margin-top":"520rpx",icon:"/static/Vertretung/noMoreData@2x.png",text:t.$t("common.text.noData")},null,8,["text"])):n("",!0)])),_:1})}],["__scopeId","data-v-9a68e56a"]]);export{x as default};
