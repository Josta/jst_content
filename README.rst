
=======================================================
JST Content: Flux-based Columns, Tabs, Maps, Sliders...
=======================================================

.. default-role:: code


:Project:
      ``TYPO3`` extension ``ext:jst_content``

:Author:
      `Josua Stabenow <josua.stabenow@gmx.de>`__

:Repository:
      Github `josta/jst_content <https://github.com/josta/jst_content>`__

:Tags: TYPO3, Extension, Flux, Content Elements, Grids

**Overview:**

.. contents::
   :local:
   :depth: 2
   :backlinks: none


What does it do?
================

The extension adds some new content element types:

- **Bootstrap Columns**, powerful yet easy to configure, with sensible defaults
- **Bootstrap Tabs**, with themes and a nice BE tab view
- **Google Maps** with any number of markers
- **Youtube Video**, privacy-enabled and bandwidth-saving
- **JSSOR Slider**, themeable slider with per-slide cover/contain options
- **Area**, a container that allows you to apply CSS classes and IDs

And it modifies standard ones:

- **Raw HTML** accepts ``Fluid`` syntax
- **Images**, **Text & Images** and **Text & Media** use responsive columns and image sizes


Requirements
============

The extension requires

- ``TYPO3 8.7.4`` or higher
- ``jQuery``
- ``Bootstrap 3 or 4`` for the Bootstrap elements

Consider installing ``JST Assets``. If available, this extension will use its SASS and CoffeeScript compilers as well as its Icon API. Otherwise you will only get precompiled assets with fixed media breakpoints and no icon sprites.

In addition, though not required, the extension is complemented by ``JST Onepage``,
which among other things adds sizing, alignment and animation options to all content elements.


Installation
============

1. Install the extension from TER (or Github)
2. Configure it in the Extension Manager (e.g. add Google API key for Maps)


The new content elements
========================

All content elements added by this extension are based on ``Flux``, which is a great framework
because of its ease of use and all-in-one approach (e.g. you don't need gridelements for grids).

Bootstrap Columns
-----------------

Why another columns element? Simply put, because all columns elements that I worked with so far
were a pain to configure and went way over the head of many content editors.

This column element tries to do better:

- it provides good defaults (2 columns of equal width, collapsing to one)
- it lets you change the layout for any Bootstrap breakpoint with one or two clicks
- you don't need to know Bootstrap to use it (no CSS classes etc.)
- there's an option to change the logical order of the columns, which is good for mobile view, bots and screen readers
- it lets you activate an equal height JS for Bootstrap 3 columns
- it lets you choose different gap / gutter width sizes
- if you still need to, you can add CSS classes to the columns manually

The generated markup is currently Bootstrap 3, which is largely compatible to Bootstrap 4. However, some of the options are implemented differently in Bootstrap 4:

- equal height columns are achieved using ``flexbox`` CSS properties
- the logical/visual order change is not done with pull and push classes, but with the ``flexbox`` property ``order``

I hope to add complete Bootstrap 3 and 4 compatible markup soon.


Bootstrap Tabs
--------------

The distinguishing features of the Tabs element are

- a nice backend view which actually visualizes the tabs and saves a lot of space
- an option to apply icons to the tabs
- (soon to come) several themes to choose from

That's all there is to say about the element, but for one little warning: Currently, if you delete a tab which still has content,
the content is essentially unassigend (but not deleted) and invisible in the Page module until you recover it with the List module.


Google Maps
-----------

The Google Maps element

- loads the Maps JS API dynamically only if there's a map on the page
- allows you to set the coordinates and zoom of the map
- lets you add any number of map markers with title and description (appear when the marker is focused/clicked)
- (soon to come) lets you set the map height and width


Youtube Video
-------------

This one is actually not just an ``iframe``:

- nothing but a static preview image is loaded from YouTube until you click on the element
- the video is loaded from ``youtube-nocookie.com``, with no advertisements, annotations or related content
- branding is minimized, controls are optional
- you can give it a max width
- you can set an aspect ratio which is always maintained
- If you set an aspect ratio that is different from what youtube provides (16:9), the video is clipped. This lets you remove black strips around the video.


JSSOR Slider
------------

This element integrates the JSSOR slider library. JSSOR creates terrible markup, but it achieves good results,
outputting a slider that can be controled with the keyboard or finger swiping.
The skins are by JSSOR as well, though I altered them just a little.

The Slider content element lets you

- choose an arrow skin and a bullet/thumbnail skin separately
- choose between different sizing options
- add any number of images with a title and optionally a link
- select between cover and contain mode for each image separately
- see a preview of the slider elements in the Backend


Area
----

This one is plain and simple: A wrapper element with an assigned ID and CSS classes.

Need some ideas what to do with it?

- apply any predefined style
- use the ID as an anchor
- (Bootstrap) create collapsed content (id="something" class="collapse") and add a toggle link somewhere else (<a href="#something" data-toggle="collapse">Show Content</a>).
- use it with content elements like "Tabs by content"/"Columns by content" which are provided by some other extensions


Fluid in HTML content elements
==============================

``JST Assets`` parses the HTML content element as a FLUIDTEMPLATE instead of just applying it raw. This allows you to use any Frontend fluid ViewHelpers. The standard namespace (f) is included by default. If you want to use other ViewHelper namespaces, you will have to declare their use at the beginning of the HTML element content, e.g. for ``VHS``:

::

	{namespace v=FluidTYPO3\Vhs\ViewHelpers}

Having Fluid in the HTML content elements raises some security concerns:

	- with Fluid you basically have read access to any content of the website and database. You usually don't have write access as far as I'm aware of (you'd have to find a ViewHelper that modifies the website, which ViewHelpers usually don't)
	- but since you shouldn't make the HTML content element available to editors that you don't fully trust anyway, you should be good
	- while breaking the website is next to impossible in Fluid, breaking the current Frontend page output is quite easy. Just give a wrong argument to a ViewHelper and visitors will see an error. So be sure to double check any Fluid code in HTML elements.
	
If you don't feel confident about the Fluid feature, you can disable it in the extension settings.


Responsive Images
=================

``JST Content`` modifies the output of images in standard content types in two ways:

- it replaces the default float-based grid structure for image columns with a Flexbox structure, which is way more flexible (pun intended) and responsive.
- it registers a new Renderer for ``jpeg/png`` images which will output the following code:

::

	<noscript><img src="x" srcset="x,y,z" width="100%" ... /></noscript>
	
Basically this means that the server provides different sizes of the image (x,y,z) to the browser, which can choose the best fitting one. The image is set to have 100% width of the parent container. Older browsers will fallback to the image URL that is given in ``src``.

Without the ``<noscript>`` wrap (or if there is no JavaScript available), the browser would choose the image version that best fits if the image were to be displayed in fullscreen. This often is way too large. With the ``<noscript>`` tag, we tell the browser not not load anything at all at first, given ``JavaScript`` is available. Then a small script snippet finds all responsive images, unwraps them and sets the ``<img>`` ``sizes`` attribute to the parent container width. This is a way of telling the browser that the ideal version would be just as wide as the image parent container.

Both the responsive image columns and the responsive image sizes can be disabled in the extension manager. The latter depends on the former, however.

