using System;
using System.Collections.Generic;
using System.Data.Entity;
using System.Linq;
using System.Web;

namespace PointOfSale.Models
{
	public class POSContext:DbContext
	{
		public DbSet<Category> Categories { get; set; }
		public DbSet<Company> Companies { get; set; }
		public DbSet<ProductStatus> ProductStatuses { get; set; }
		public DbSet<UserRole> UserRoles { get; set; }
		public DbSet<UserStatus> UserStatuses { get; set; }
		public DbSet<Suppliers> Suppliers { get; set; }
		public DbSet<Users> Users { get; set; }
		public DbSet<Retail> Retails { get; set; }
		public DbSet<WholeSale> Wholesales { get; set; }

		
	}
}