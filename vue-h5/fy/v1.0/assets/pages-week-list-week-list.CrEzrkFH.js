import{ai as t,x as a,y as s,z as e,A as l,C as o,D as i,E as r,F as u,G as m,H as d,I as n,J as p}from"./index-DgmPZ0G5.js";import{_}from"./u-loadmore.C0MNBupu.js";import{r as c}from"./uni-app.es.DuVg4RGY.js";import{_ as h}from"./u-empty.B_4P7i6r.js";import{S as g}from"./index.DJMEk9DT.js";import{_ as f}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./u-line.PUIrZZ8-.js";import"./u-loading-icon.Dk-dhO9O.js";import"./u-icon.CMhWs0pO.js";const j=f({data:()=>({list:[],page:1,per_page:1,status:"loadmore"}),mounted(){this.getData()},methods:{getData(){g({page:this.page}).then((t=>{this.list=t.data.data,this.per_page=t.data.last_page}))},onReachBottom(){this.page>=this.per_page&&(this.status="nomore"),this.page>=this.per_page||(this.status="loading",this.page++,setTimeout((()=>{this.getData()}),0))}},onReachBottom(){this.onReachBottom()},onPullDownRefresh(){console.log("我是主动下拉刷新、"),this.page=1,this.list=[],this.getData(),setTimeout((function(){t()}),1e3)}},[["render",function(t,g,f,j,x,y){const D=o,k=c(a("up-loadmore"),_),R=c(a("up-empty"),h);return s(),e(D,null,{default:l((()=>[(s(!0),i(u,null,r(x.list,((t,a)=>(s(),e(D,{class:"page_list_item",key:a},{default:l((()=>[d(D,{style:{height:"30rpx"}}),d(D,{class:"list_header"},{default:l((()=>[d(D,{class:"munds1"},{default:l((()=>[d(D,{class:"mr-5"},{default:l((()=>[n(p(t.name),1)])),_:2},1024),d(D,{class:"mr-5"},{default:l((()=>[n("（一键优投）")])),_:1})])),_:2},1024),d(D,{class:"munds2"},{default:l((()=>[d(D,{class:"mr-5"},{default:l((()=>[n("优投金额")])),_:1})])),_:1})])),_:2},1024),d(D,{class:"list_middle"},{default:l((()=>[d(D,{class:"page_head"},{default:l((()=>[d(D,{class:"mr-5"},{default:l((()=>[n("订单号:"+p(t.order_sn),1)])),_:2},1024)])),_:2},1024),d(D,{class:"yui-la"},{default:l((()=>[d(D,{class:"mr-5"},{default:l((()=>[n("￥"+p(t.money),1)])),_:2},1024)])),_:2},1024)])),_:2},1024),d(D,{class:"list_time"},{default:l((()=>[d(D,{class:"mr-6"},{default:l((()=>[n(p(t.create_time_text),1)])),_:2},1024)])),_:2},1024)])),_:2},1024)))),128)),x.list.length>0?(s(),e(k,{key:0,status:x.status,onClick:y.onReachBottom},null,8,["status","onClick"])):m("",!0),0==x.list.length?(s(),e(R,{key:1,mode:"data",width:"200","margin-top":"520rpx",icon:"/static/Vertretung/noMoreData@2x.png"})):m("",!0)])),_:1})}],["__scopeId","data-v-eaf7d7d5"]]);export{j as default};
