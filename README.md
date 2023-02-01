# DigiComp.NextActionViews

This package contains two views: 
- RedirectToActionView
- ForwardToActionView

These views help you NOT to use ->redirect and ->forward in your ActionControllers and by doing so stick to a clean separation of concerns between the controller and the views.

It is especially useful, if you have controllers coming from your packages, which should redirect to different targets in different projects.
