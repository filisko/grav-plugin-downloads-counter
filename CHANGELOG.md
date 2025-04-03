# Change Log

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).


## 0.2.0 - 2025-04-03
### Added
A new form field was added to the configuration page in order to add the possibility to ignore files from being counted by their name.
Each line must contain a regex expression compatible with `preg_match()`. Example:
```text
/\.zip$/
/\.sh/
```

## 0.1.0 - 2017-01-16
First version

[0.2.0]: https://github.com/middlewares/aura-session/compare/v0.1.0...v0.2.0
