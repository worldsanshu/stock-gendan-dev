import{ah as t,x as a,y as e,z as s,A as o,C as l,D as i,E as m,F as n,G as r,H as d,I as p,J as u}from"./index-BI_M_jYu.js";import{_ as c}from"./u-loadmore.5UwRh44Y.js";import{r as _}from"./uni-app.es.CYmSWya5.js";import{_ as h}from"./u-empty.C7IbtxoX.js";import{o as g,q as f}from"./index.B27wsiGI.js";import{_ as x}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./u-line.DvVv571C.js";import"./u-loading-icon.DsT6vaCF.js";import"./u-icon.BII4vsMV.js";const y=x({data:()=>({list:[],page:1,per_page:1,status:"loadmore",type:"1"}),onLoad(t){console.log(t),this.type=t.type??"1"},mounted(){this.getData()},methods:{getData(){({1:g,2:f})[this.type]({page:this.page,is_today:1}).then((t=>{this.list=this.list.concat(1*this.type==2?t.data.data??[]:t.data.list.data??[]),this.per_page=1*this.type==2?t.data.last_page:t.data.list.last_page}))},onReachBottom(){this.page>=this.per_page&&(this.status="nomore"),this.page>=this.per_page||(this.status="loading",this.page++,setTimeout((()=>{this.getData()}),0))}},onReachBottom(){this.onReachBottom()},onPullDownRefresh(){console.log("我是主动下拉刷新、"),this.page=1,this.list=[],this.getData(),setTimeout((function(){t()}),1e3)}},[["render",function(t,g,f,x,y,j){const D=l,$=_(a("up-loadmore"),c),k=_(a("up-empty"),h);return e(),s(D,null,{default:o((()=>[(e(!0),i(n,null,m(y.list,((a,l)=>(e(),s(D,{class:"page_list_item",key:l},{default:o((()=>[d(D,{style:{height:"30rpx"}}),d(D,{class:"list_header"},{default:o((()=>[d(D,{class:"munds1"},{default:o((()=>[d(D,{class:"mr-5"},{default:o((()=>[p(u(a.name),1)])),_:2},1024),d(D,{class:"mr-5"},{default:o((()=>[p("（"+u(t.$t("common.text.oneClickSmartInvestment"))+"）",1)])),_:1})])),_:2},1024),d(D,{class:"munds2"},{default:o((()=>[d(D,{class:"mr-5"},{default:o((()=>[p(u(t.$t("common.text.orderAmount")),1)])),_:1})])),_:1})])),_:2},1024),d(D,{class:"list_middle"},{default:o((()=>[d(D,{class:"page_head"},{default:o((()=>[d(D,{class:"mr-5"},{default:o((()=>[p(u(t.$t("common.text.orderNumber"))+u(a.order_sn),1)])),_:2},1024)])),_:2},1024),d(D,{class:"yui-la"},{default:o((()=>[d(D,{class:"mr-5"},{default:o((()=>[p("￥"+u(a.money),1)])),_:2},1024)])),_:2},1024)])),_:2},1024),d(D,{class:"list_time"},{default:o((()=>[d(D,{class:"mr-6"},{default:o((()=>[p(u(a.create_time_text),1)])),_:2},1024)])),_:2},1024)])),_:2},1024)))),128)),y.list.length>0?(e(),s($,{key:0,status:y.status,onClick:j.onReachBottom,"loading-text":t.$t("common.text.loading"),"loadmore-text":t.$t("common.text.loadMore"),"nomore-text":t.$t("common.text.noMore")},null,8,["status","onClick","loading-text","loadmore-text","nomore-text"])):r("",!0),0==y.list.length?(e(),s(k,{key:1,mode:"data",width:"200","margin-top":"520rpx",icon:"/static/Vertretung/noMoreData@2x.png",text:t.$t("common.text.noData")},null,8,["text"])):r("",!0)])),_:1})}],["__scopeId","data-v-2d526b01"]]);export{y as default};
