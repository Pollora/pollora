# Contribution Guide

## Introduction

Thank you for taking the time to contribute to the Pollora project! This project aims to integrate WordPress into a Laravel application. We adhere to Laravel's development conventions and respect Git Flow's branch naming conventions.

## Prerequisites

1. Install Git on your local machine.
2. Fork the Pollora projet: [https://github.com/AmphiBee/pollora](https://github.com/AmphiBee/pollora).
3. Clone your fork on your local machine.

> **Note:** Installing Git Flow is optional, but if you choose not to use it, please adhere to the branch naming convention as described below.

## Contribution Workflow

### Branch Naming Convention (Git Flow)

- **Features** : `feature/feature_name`
- **Bug Fixes** : `hotfix/bug_name`
- **Releases** : `release/version_number`
- **Maintenance or Miscellaneous Tasks** : `support/task_name`

1. Create a new branch following the naming convention: `git checkout -b feature/feature_name`.
2. Make your changes while adhering to Laravel's development conventions.
3. Test your changes locally.
4. Run the "Pint" tool to validate code quality before committing.

### Documentation

- **Technical Part**: Document classes, functions, methods, and variables using appropriate code comments.
- **Functional Part**: Update Pollora's documentation on the corresponding repository: [Pollora Documentation](https://github.com/AmphiBee/pollora-documentation).

## Submitting the Pull Request

1. Commit your changes: `git commit -m "Description of changes"`.
2. Push your branch to your fork: `git push origin feature/feature_name`.
3. Create a Pull Request via GitHub.

## Reporting Bugs

### How to Report a Bug

1. **Check Existing Issues**: Before submitting a new bug report, please check the existing Issues on GitHub to see if someone has already reported the problem.
2. **Use the Bug Template**: If the bug has not been reported, please use the bug report template provided by the repository.
3. **Provide Detailed Information**: Make sure to include all necessary details such as your environment, steps to reproduce, and any other relevant information that could help in debugging.

### What to Include in a Bug Report

- **Description**: A clear and concise description of what the bug is.
- **Steps to Reproduce**: A step-by-step guide to reproduce the bug.
- **Expected Behavior**: What you expected to happen.
- **Actual Behavior**: What actually happened.
- **Environment**: Information about the system where the bug occurred, such as OS, browser, etc.
- **Screenshots**: If applicable, add screenshots to help explain the problem.
- **Additional Context**: Any other information that could be relevant to the bug report.

### Label the Issue

After submitting, please label the bug report with the `bug` label provided in GitHub.

### Follow-Up

Please try to keep the conversation focused on fixing the reported bug. We appreciate your help in making Pollora better.

## Support

For any questions or issues, open an Issue on GitHub or contact us at `dev@amphibee.fr`.
