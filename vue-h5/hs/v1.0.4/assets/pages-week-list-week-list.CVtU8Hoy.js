import{aj as t,x as a,y as e,z as s,A as l,C as o,D as i,E as n,F as r,G as u,H as m,I as d,J as p,O as g}from"./index-DKPlMZwP.js";import{_}from"./u-loadmore.IBxWUiPJ.js";import{r as c}from"./uni-app.es.DA8hZIlc.js";import{_ as h}from"./u-empty.DXc2gTHf.js";import{S as f}from"./index.CKJZ6KQP.js";import{_ as j}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./u-line.BGXf8ogQ.js";import"./u-loading-icon.B7qGBqey.js";import"./u-icon.Ah-ieCQw.js";const y=j({data:()=>({list:[],page:1,per_page:1,status:"loadmore"}),mounted(){this.getData()},methods:{getData(){f({page:this.page}).then((t=>{this.list=t.data.data,this.per_page=t.data.last_page}))},onReachBottom(){this.page>=this.per_page&&(this.status="nomore"),this.page>=this.per_page||(this.status="loading",this.page++,setTimeout((()=>{this.getData()}),0))},timeStampConvert:function(t){var a=new Date(1e3*t);console.log(a),console.log(a.getFullYear());var e=a.getFullYear(),s=a.getMonth()+1,l=a.getDate(),o=a.getHours(),i=a.getMinutes();return console.log({year:e,month:s,date:l}),e+"-"+s+"-"+l+" "+o+":"+i}},onReachBottom(){this.onReachBottom()},onPullDownRefresh(){console.log("我是主动下拉刷新、"),this.page=1,this.list=[],this.getData(),setTimeout((function(){t()}),1e3)}},[["render",function(t,f,j,y,D,x){const v=o,k=g,B=c(a("up-loadmore"),_),C=c(a("up-empty"),h);return e(),s(v,null,{default:l((()=>[(e(!0),i(r,null,n(D.list,((t,a)=>(e(),s(v,{class:"page_list_item",key:a},{default:l((()=>[m(v,{style:{height:"30rpx"}}),m(v,{class:"list_header"},{default:l((()=>[m(v,{class:"munds1"},{default:l((()=>[m(v,{class:"mr-5"},{default:l((()=>[d(p(t.name),1)])),_:2},1024),m(v,{class:"mr-5"},{default:l((()=>[m(k,null,{default:l((()=>[d("(一键优投) ")])),_:1}),m(k,null,{default:l((()=>[d("手机:"+p(t.mobile),1)])),_:2},1024)])),_:2},1024)])),_:2},1024),m(v,{class:"munds2"},{default:l((()=>[m(v,{class:"mr-5"},{default:l((()=>[d("优投金额")])),_:1})])),_:1})])),_:2},1024),m(v,{class:"list_middle"},{default:l((()=>[m(v,{class:"page_head"},{default:l((()=>[m(v,{class:"mr-5"},{default:l((()=>[d("订单号:"+p(t.order_sn),1)])),_:2},1024)])),_:2},1024),m(v,{class:"yui-la"},{default:l((()=>[m(v,{class:"mr-5"},{default:l((()=>[d("￥"+p(t.money),1)])),_:2},1024)])),_:2},1024)])),_:2},1024),m(v,{class:"list_time"},{default:l((()=>[m(v,{class:"mr-6"},{default:l((()=>[d("购买时间:"+p(x.timeStampConvert(t.create_time)),1)])),_:2},1024)])),_:2},1024)])),_:2},1024)))),128)),D.list.length>0?(e(),s(B,{key:0,status:D.status,onClick:x.onReachBottom},null,8,["status","onClick"])):u("",!0),0==D.list.length?(e(),s(C,{key:1,mode:"data",width:"200","margin-top":"520rpx",icon:"/static/Vertretung/noMoreData@2x.png"})):u("",!0)])),_:1})}],["__scopeId","data-v-33012b1c"]]);export{y as default};
