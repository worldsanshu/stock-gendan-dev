import{x as s,y as a,z as t,A as e,C as l,H as i,I as n,J as o,K as u,D as d,E as r,F as _,G as c}from"./index-DKUjMFTs.js";import{_ as p}from"./u-loadmore.iMjU_XNl.js";import{r as m}from"./uni-app.es.UMZ84mjS.js";import{_ as f}from"./u-empty.OWMABB6H.js";import{R as h,x as g}from"./index.DDVMl6IJ.js";import{_ as b}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./u-line.CngD2Soa.js";import"./u-loading-icon.CEkpOwBA.js";import"./u-icon.DqEvg06s.js";const v=b({data:()=>({page:1,per_page:0,allList:[],investorDetail:"",investorlist:"",status:"loadmore",id:""}),methods:{onReachBottom(){this.page>=this.per_page&&(this.status="nomore"),this.page>=this.per_page||(this.status="loading",this.page++,setTimeout((()=>{this.getInve()}),0))},getInve(){h({trader_id:this.id,page:this.page}).then((s=>{for(let a=0;a<s.data.list.data.length;a++)this.allList.push(s.data.list.data[a]);this.per_page=s.data.list.last_page}))}},onLoad(s){this.id=s.id,h({trader_id:s.id,page:this.page}).then((s=>{console.log("接口",s.data.list.last_page);for(let a=0;a<s.data.list.data.length;a++)this.allList.push(s.data.list.data[a]);this.per_page=s.data.list.last_page})),g({id:s.id}).then((s=>{this.investorDetail=s.data.form,this.investorlist=s.data.list}))}},[["render",function(h,g,b,v,D,j){const w=l,x=m(s("up-loadmore"),p),y=m(s("up-empty"),f);return a(),t(w,null,{default:e((()=>[i(w,{class:"content-area"},{default:e((()=>[i(w,{class:"record"},{default:e((()=>[i(w,{class:"mun"},{default:e((()=>[i(w,{class:"mun-tse"},{default:e((()=>[n("总收益")])),_:1}),i(w,{class:"mun-bse"},{default:e((()=>[n(o(D.investorDetail.total_revenue)+"%",1)])),_:1})])),_:1}),i(w,{class:"mun-se"},{default:e((()=>[i(w,{class:"mun-selse"},{default:e((()=>[i(w,{class:"mun-zis"},{default:e((()=>[n("月收益")])),_:1}),i(w,{class:"mun-zis"},{default:e((()=>[n(o(D.investorDetail.monthly_revenue)+"%",1)])),_:1})])),_:1}),i(w,{class:"mun-selse"},{default:e((()=>[i(w,{class:"mun-zis"},{default:e((()=>[n("仓位")])),_:1}),i(w,{class:"mun-zis"},{default:e((()=>[n(o(D.investorDetail.position)+"%",1)])),_:1})])),_:1}),i(w,{class:"mun-selse"},{default:e((()=>[i(w,{class:"mun-zis"},{default:e((()=>[n("最大回撤")])),_:1}),i(w,{class:"mun-zis"},{default:e((()=>[n(o(D.investorDetail.maximum_rollback)+"%",1)])),_:1})])),_:1}),i(w,{class:"mun-selse"},{default:e((()=>[i(w,{class:"mun-zis"},{default:e((()=>[n("胜率")])),_:1}),i(w,{class:"mun-zis"},{default:e((()=>[n(o(D.investorDetail.win_obbs)+"%",1)])),_:1})])),_:1})])),_:1})])),_:1}),i(w,{class:"munt"},{default:e((()=>[i(w,{class:"mtois"}),i(w,{class:"follow-description"},{default:e((()=>[i(w,{class:"todn"}),i(w,{class:"base-title"},{default:e((()=>[n("持仓明细")])),_:1})])),_:1})])),_:1}),i(w,{class:"table"},{default:e((()=>[i(w,{class:"tabblse"},{default:e((()=>[i(w,{class:"tabblse-sis"},{default:e((()=>[i(w,{class:"tabblse-lse"},{default:e((()=>[n("本周收益率:")])),_:1}),i(w,{class:"tabblse-rse"},{default:e((()=>[n(o(D.investorDetail.week_ratio)+"%",1)])),_:1})])),_:1}),i(w,{class:"tabblse-sis"},{default:e((()=>[i(w,{class:"tabblse-lse"},{default:e((()=>[n("本月收益率:")])),_:1}),i(w,{class:"tabblse-rse"},{default:e((()=>[n(o(D.investorDetail.month_ratio)+"%",1)])),_:1})])),_:1})])),_:1}),i(w,{class:"table-head"},{default:e((()=>[u("span",null,"历史持仓"),u("span",null,"成本价"),u("span",null,"浮动收益"),u("span",null,"买入时间")])),_:1}),(a(!0),d(_,null,r(D.allList,((s,l)=>(a(),t(w,{class:"table-row",key:l},{default:e((()=>[i(w,{class:"table-row-span"},{default:e((()=>[n(o(s.stockname),1)])),_:2},1024),i(w,{class:"table-row-span"},{default:e((()=>[n(o(s.buy_cost),1)])),_:2},1024),i(w,{class:"table-row-span"},{default:e((()=>[n(o(s.floating_ratio),1)])),_:2},1024),i(w,{class:"table-row-span"},{default:e((()=>[n(o(s.buy_time),1)])),_:2},1024)])),_:2},1024)))),128))])),_:1})])),_:1}),D.allList.length>0?(a(),t(x,{key:0,status:D.status,onClick:j.onReachBottom,line:""},null,8,["status","onClick"])):c("",!0),D.allList.length?c("",!0):(a(),t(y,{key:1,mode:"data",width:"160","margin-top":"150rpx",icon:"/static/Vertretung/noMoreData@2x.png"}))])),_:1})}],["__scopeId","data-v-4a878a1f"]]);export{v as default};