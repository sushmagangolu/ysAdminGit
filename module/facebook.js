var fb_config = {
    graph_url = 'https://graph.facebook.com/v2.8/'
    access_token: 'EAAQMRkcZCyEgBAOhWuY5eRehpZBSsTSRid6Q2O18fVedgtZA4FDq0fEZCi9395V8irs4b7nHb42sPs8RlrCZCoeDUU1gMUcymAqbYPtPv9vUPNFb7WCEct56mRYwAZAYvRQELRiIAcv9J0hu1Dy9veIy3aoYpZCGgNbDohKUmJy7AZDZD',
    fields_posts: 'id,name,picture,posts{shares,likes,comments,message,story,created_time,picture}'
}
var facebook = {
        init: function() {
            this.getFacebookPosts();
        },
        getFacebookPosts: function() {
            var graphURL = graph_url + facebook_page_id + '?fields=' + fb_config.fields_posts + '&access_token=' + fb_config.access_token;
            $.getJSON(graphURL, function(result) {
                    console.log(result);
                }
            }
        }
