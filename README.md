# Table of contents plugin for Craft CMS 3.x

This plugin generates a table of contents from HTML headers in text. Anchor links in table direct to corresponding headers in text.

Table of contents can be nested - nesting level will be based on header level.

![Screenshot](resources/example.png)

## Requirements

This plugin requires Craft CMS 3.0.0-beta.23 or later.

It also requires [Anchors plugin](https://plugins.craftcms.com/anchors), since it makes use of its internal functions.

## Usage

To use the table of contents, you need to pass HTML containing headers to `craft.toc.getLinks` function and output returned table of contents using `{% nav %}` Twig tag. 

Don't forget to also put your HTML through `anhors` filter provided by Anchors plugin - otherwise, anchor links inside the table of contents won't have anything to direct to.

Remember to use `anchors` filter **after** passing HTML to `getLinks` function. Otherwise, links in table of contents would contain also escaped HTML content of links added to the text by Anchors plugin.

```
{% set text %}
some text with headers...
{% endset %}

{% table = craft.toc.getLinks(text) %}

 <ul>
    {% nav link in toc %}
        <li>
            <a href="{{ link.hash }}">{{ link.text }}</a>
            {% ifchildren %}
                <ul>
                    {% children %}
                </ul>
            {% endifchildren %}
        </li>
    {% endnav %}
</ul>

{{text|anchors}}
```
Don't forget to give your links bit of left margin to show their hierarchy.

```
li{
  margin-left: 1rem;
}
```

## Nested numeric list

To display numeric count before links within the table of contents, you can use bit of CSS. This will work also for nested lists.

```
ul {
  counter-reset: section;               
  list-style-type: none;
}
li::before {
  counter-increment: section;            
  content: counters(section, ".") " "; 
}
```

## Alternative header tags

By default, Table of contents plugin searches for `h1`, `h2` and `h3` tags. Just like in Anchors plugin, this can be overwritten by passing the second argument to `getLinks` function.

```
{% table = craft.toc.getLinks(text, 'header1,header2,header3') %}
```

Don't forget to do the same when using `anchors` filter.

## Smooth scrolling

You can achieve smooth scrolling effect with a bit of jQuery code. If a user start to scroll (using mouse scroll wheel) during an animation, scrolling will be canceled to avoid "fighting" with it.

Despite animation replacing `click` event, hash will still be appended to URL and browser back and forward buttons will work - thanks to use of JavaScript history API.

```
  $('.table-of-contents a').on('click', function(event) {
    var hash = '#' + $(this).attr('href').split('#')[1]
    var element = $(hash)
    if (element.length) {
      event.preventDefault();
      history.pushState(hash, undefined, hash)
      $('html, body').animate({scrollTop: element.offset().top}, 500)
    }
  });   

  window.addEventListener('popstate', function(e) {
    if(e.state && $(e.state).length){
      $('html, body').animate({scrollTop: $(e.state).offset().top}, 500)
    }
  });


  $('html, body').on("scroll mousedown wheel DOMMouseScroll mousewheel keyup touchmove", function(){
    $('html, body').stop();
  });
```

Brought to you by [Piotr Pogorzelski](http://craftsnippets.com)