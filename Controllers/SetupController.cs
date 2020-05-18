using PointOfSale.Data.repository;
using PointOfSale.Models.ViewModels;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;

namespace PointOfSale.Controllers
{
    public class SetupController : Controller
    {
        private readonly CategoryRepository _repository = new CategoryRepository();
        // GET: Setup
        public ActionResult Index()
        {
            var categories = _repository.GetCategories();
            if (categories == null)
            {
                return RedirectToAction("Index");
            }
            ViewBag.getCategories = categories.ToList();
            
                return View();
            }

           
        

       
    }
}