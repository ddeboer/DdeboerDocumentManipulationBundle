[![Build Status](https://secure.travis-ci.org/ddeboer/DdeboerDocumentManipulationBundle.png)](http://travis-ci.org/ddeboer/DdeboerDocumentManipulationBundle)

Ddeboer Document Manipulation Bundle
===================================

Introduction
------------

Use this bundle to perform operations on PDF and Microsoft Doc documents.  The
goal of this bundle to abstract away mail merging, appending, etc. of word
processor documents.

### Features

(todo)

Installation
------------

This bundle is available on [Packagist](http://packagist.org/packages/ddeboer/document-manipulation-bundle).

### Mail merging using LiveDocx

If you want to use the `LiveDocxManipulator` for mail merging, get an account
at [LiveDocx](http://www.livedocx.com), and install
[ZendService\LiveDocx](https://github.com/zendframework/ZendServiceLiveDocx)
by adding the following to your `composer.json`:

```
    "repositories": [
        {
            "type": "composer",
            "url": "http://packages.zendframework.com/"
        },
        ...
    ],
    "require": {
        "zendframework/zendservice-livedocx": "@stable",
    }

```

Then configure LiveDocx in your `config.yml`:

```
ddeboer_document_manipulation:
  livedocx:
    username: [your LiveDocx username]
    password: [your LiveDocx password]
    wsdl: [your premium LiveDocx WSDL, if you have a premium account]
```

### PDF manipulation using pdftk

Install `pdftk`, and then configure it in your `config.yml`:

```
ddeboer_document_manipulation:
  pdftk:
    binary: /usr/local/bin/pdftk
```

Replace `/usr/local/bin/pdftk` with the path to the `pdftk` binary on your
system.

Customization
-------------

### Create a custom document manipulator

Create your own document manipulator, and implement the `ManipulatorInterface`.
Add your manipulator as a service, and tag that with
`ddeboer_document_manipulation.manipulator`. For instance the standard LiveDocx
manipulator is defined as follows:

```
<service id="ddeboer_document_manipulation.manipulator.live_docx"
         class="Ddeboer\DocumentManipulationBundle\Manipulator\LiveDocxManipulator">
    <tag name="ddeboer_document_manipulation.manipulator" />
</service>
```

Documentation
-------------

More extensive documentation will be included in the [Resources/doc directory](http://github.com/ddeboer/DdeboerDocumentManipulationBundle/tree/master/Resources/doc/index.md).