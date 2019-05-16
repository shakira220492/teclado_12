var resultStatus = "";
var totalVideos = 0;

function appendVideoToSearchEngineDiv(
    videoId,
    videoName,
    videoDescription,
    videoImage,
    videoContent,
    videoUpdatedate,
    videoAmountViews,
    videoAmountComments,
    videoLikes,
    videoDislikes,
    userId,
    userName,
    amountVideos,
    percentageLikes,
    percentageDislikes,
    respectlyId,
    kindOfList,
    listId,
    videoOrderPosition
    )
{
    
    $("#searchResults").append(
        "<div id='videoPortrait_div"+respectlyId+"' style='position: relative; width:100%; cursor: pointer;' "
            +" data-id1='"+videoId+"'"
            +" data-id2='"+videoName+"'"
            +" data-id3='"+videoDescription+"'"
            +" data-id4='"+videoImage+"'"
            +" data-id5='"+videoContent+"'"
            +" data-id6='"+videoUpdatedate+"'"
            +" data-id7='"+videoAmountViews+"'"
            +" data-id8='"+videoAmountComments+"'"
            +" data-id9='"+videoLikes+"'"
            +" data-id10='"+videoDislikes+"'"
            +" data-id11='"+userId+"'"
            +" data-id12='"+userName+"'"
            +" data-id13='"+amountVideos+"'"
            +" data-id14='"+percentageLikes+"'"
            +" data-id15='"+percentageDislikes+"'"
            +" >"

            +"<table border='0' width='100%'>"
            +"<tr height='120px'>"
                +"<td width='170px' height='100px'>"
                    +"<div id='videoPortrait_"+respectlyId+"' class='videoPortrait_'>"
                        +"<img style='"
                        +"height:100%; cursor: pointer;"
                        +"opacity: 0.9; "
                        +"z-index: 1;' "
                        +"src='files/"+videoImage+"' "
                        +"alt='Mountain View'>"
                    +"</div>"
                    +"<div id='videoContaint_"+respectlyId+"' class='videoContaint_'>"
                        +" <b>"+videoName+"</b><br>"
                        +" "+userName+"<br>"
                        +" "+videoUpdatedate+"<br>"
                        +" "+videoAmountViews+" views<br>"
                        +" "+videoAmountComments+" comments<br>"
                        +" <div style='height: 5px; width: 100%;'>"
                            +"<div style='display: inline-block; height: 5px; width: "+percentageLikes+"%; background-color: blue; '></div>"
                            +"<div style='display: inline-block; height: 5px; width: "+percentageDislikes+"%; background-color: red; '></div>"
                        +" </div>"
                    +"</div>"
                +"</td>"
            +"</tr>"
            +"</table>"

        +"</div>"
    );

    $('#videoPortrait_div'+respectlyId)
    .mouseover(function () {

        document.getElementById("videoPortrait_"+respectlyId).style.opacity = 0.5;
        document.getElementById("videoContaint_"+respectlyId).style.opacity = 1;
        document.getElementById("searchResults").style.opacity = 1;
        document.getElementById("searchBar").style.opacity = 1;
    })
    .click(function () {

        showVideo(
            videoId,
            videoName,
            videoDescription,
            videoImage,
            videoContent,
            videoUpdatedate,
            videoAmountViews,
            videoAmountComments,
            videoLikes,
            videoDislikes,
            userId,
            userName,
            kindOfList,
            listId,
            videoOrderPosition
        );

    })
    .mouseout(function () {
        document.getElementById("videoPortrait_"+respectlyId).style.opacity = 1;
        document.getElementById("videoContaint_"+respectlyId).style.opacity = 0;
        document.getElementById("searchResults").style.opacity = 0.5;
        document.getElementById("searchBar").style.opacity = 0.5;
    });
    
    $('#searchResults')
    .mouseover(function () {
        var staticCommentVideoSection = document.getElementById("searchResults");
        staticCommentVideoSection.classList.add("visibleScroll");
    })
    .mouseout(function () {
        var staticCommentVideoSection = document.getElementById("searchResults");
        staticCommentVideoSection.classList.remove("visibleScroll");
    });
    
}

function drawNoResult(event, amountVideos) 
{
    if(event != "scroll" && amountVideos === 0)
    {
        $("#searchResults").append(
            "<div>"
                +"<table border='0' width='100%'>"
                +"<tr height='150px'>"
                    +"<td width='250px' height='150px'>"
                        +"<div>"
                            +" <b>There isn't more results for this search.</b><br>"
                        +"</div>"
                    +"</td>"
                +"</tr>"
                +"</table>"
            +"</div>"
        );
    }
}