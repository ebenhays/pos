using System;
using System.Collections.Generic;
using System.Linq;
using System.Security.Permissions;
using System.Web;

namespace PointOfSale.Models.ViewModels
{
	public class SetupViewModel
	{
		public Category Category { get; set; }
		public Company Company { get; set; }
		public ProductStatus ProductStatus { get; set; }
		public UserRole UserRole { get; set; }
		public UserStatus UserStatus { get; set; }
		public Suppliers Suppliers { get; set; }
		public Users Users { get; set; }
	}
}