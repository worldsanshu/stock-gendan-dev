import{V as t,x as a,y as e,D as s,H as l,Y as o,A as i,F as n,ak as r,C as u,Z as c,I as d,J as f}from"./index-BSZMSohE.js";import{_ as p}from"./uni-nav-bar.DaV7kknO.js";import{r as m}from"./uni-app.es.xTxZrAO-.js";import{_}from"./u-link.BTnagf1d.js";import{_ as v}from"./u-skeleton.v08ghf2y.js";import"./uni-icons.UvJfiB1T.js";import"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-status-bar.DM8Vigxm.js";const g={__name:"stock-detail-article",props:{id:String,code:String},setup(g){const k=g,j=t();return async function(){var t;const a=await r({url:"https://data.001ghsp.com//stock/notice/dails",method:"POST",data:{id:k.id,code:k.code}});200===a.statusCode&&(j.value=null==(t=a.data.result)?void 0:t.data),console.log("info",j)}(),(t,r)=>{const g=m(a("uni-nav-bar"),p),k=u,x=m(a("up-link"),_),h=m(a("up-skeleton"),v);return e(),s(n,null,[l(g,{title:t.$t("page.title.stockDetailArticle"),"status-bar":"","left-icon":"left",fixed:"",onClickLeft:o(c)},null,8,["title","onClickLeft"]),l(k,{class:"root p-30"},{default:i((()=>[l(h,{rows:"10",title:"",loading:!j.value},{default:i((()=>[l(k,{class:"title font-18"},{default:i((()=>[d(f(j.value.Title),1)])),_:1}),l(k,{class:"align-center font-12-grey mt-20"},{default:i((()=>[l(k,null,{default:i((()=>[d(f(j.value.origin),1)])),_:1}),l(k,{class:"ml-10"},{default:i((()=>[d(f(j.value.end_date),1)])),_:1})])),_:1}),l(k,{class:"content mt-20"},{default:i((()=>[d(f(j.value.Content),1)])),_:1}),l(x,{class:"mt-20",href:j.value.pdf_path,text:t.$t("stockDetailArticle.text.tip")},null,8,["href","text"])])),_:1},8,["loading"])])),_:1})],64)}}};export{g as default};
