# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.1.5] - 2021-03-04

### Added

- Returns a deep cloned instance via `Type::copy()` and `TypeArray::copy()`;

## [0.1.4] - 2021-03-04

### Fixed

- Call to `Type::toArray()` should sync with processed attributes;

### Changed

- `TypeArray::items()` renamed to `TypeArray::toArray()`;

## [0.1.3] - 2020-11-11

### Fixed

- When an attribute from `Type` or an item from `TypeArray` is modified it should be reprocessed;

## [0.1.2] - 2020-11-06

### Fixed

- The `$parent` property applied during casting will be `null` if the target casting class represents a non-`Type` instance, avoiding possible errors (for example when `__set()` on target class not can handle correctly this behavior);

## [0.1.1] - 2020-11-06

### Added

- `TypeArray` now implements `Iterator`, `JsonSeriazable` and `Countable`;

## [0.1.0] - 2020-11-06

### Added

- Initial version;

[0.1.5]: https://github.com/rentalhost/vanilla-type/compare/0.1.4..0.1.5

[0.1.4]: https://github.com/rentalhost/vanilla-type/compare/0.1.3..0.1.4

[0.1.3]: https://github.com/rentalhost/vanilla-type/compare/0.1.2..0.1.3

[0.1.2]: https://github.com/rentalhost/vanilla-type/compare/0.1.1..0.1.2

[0.1.1]: https://github.com/rentalhost/vanilla-type/compare/0.1.0..0.1.1

[0.1.0]: https://github.com/rentalhost/vanilla-type/tree/0.1.0
